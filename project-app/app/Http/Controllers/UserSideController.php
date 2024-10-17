<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zxing\QrReader;
use App\Models\assetModel;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\SystemNotification;

class UserSideController extends Controller
{
    // REQUEST LIST
    public function showRequestList(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $sort_by = $request->input('sort_by', 'created_at'); // Default sorting by 'created_at'
        $sort_direction = $request->input('sort_direction', 'desc'); // Default sorting direction is 'desc'
        $status = $request->input('status');  // Filter by status
        $type = $request->input('type');  // Filter by type
        $from_date = $request->input('from_date');  // Date range (start)
        $to_date = $request->input('to_date');  // Date range (end)

        // Fetch requests made by the current user and join with related tables
        $requests = DB::table('maintenance')
            ->join('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->join('model', 'asset.model_key', '=', 'model.id')
            ->join('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->join('location', 'asset.loc_key', '=', 'location.id')
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->where('maintenance.requestor', $userId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('maintenance.description', 'like', '%' . $search . '%')
                        ->orWhere('maintenance.status', 'like', '%' . $search . '%')
                        ->orWhere('asset.code', 'like', '%' . $search . '%')
                        ->orWhere('maintenance.id', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('maintenance.status', $status);
            })
            ->when($type, function ($query, $type) {
                return $query->where('maintenance.type', $type);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                return $query->whereBetween('maintenance.created_at', [$from_date, $to_date]);
            })
            ->select(
                'maintenance.*',
                'asset.code as asset_code',
                'asset.status as asset_status',
                'asset.asst_img as asset_image', // Use the correct column name
                'asset.qr_img as qr_code', // Use the correct QR column name
                'asset.name as asset_name',
                'asset.depreciation',
                'asset.salvage_value as salvageVal', // Use correct column name
                'asset.usage_lifespan as usage_Lifespan', // Correct casing
                'category.name as category',
                'model.name as model',
                'manufacturer.name as manufacturer',
                'location.name as location',
                'department.name as department'
            )
            ->orderBy($sort_by, $sort_direction)
            ->paginate(5);

        return view('user.requestList', [
            'requests' => $requests,
            'search' => $search,
            'status' => $status,
            'type' => $type,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }


    public function createRequest(Request $request)
    {
        // Validate the input from the form
        $request->validate([
            'asset_id' => 'required|exists:asset,id', // Ensure the asset exists
            'issue_description' => 'required|string|max:1000',
            'type' => 'required|in:repair,maintenance,upgrade,inspection', // Validate the request type
        ]);

        // Create a new maintenance request with 'pending' status
        Maintenance::create([
            'description' => $request->input('issue_description'), // Issue description
            'status' => 'request', // Set status to 'pending'
            'asset_key' => $request->input('asset_id'), // Asset reference
            'requestor' => Auth::id(), // Logged-in user as requestor
            'type' => $request->input('type'), // Request type
        ]);

        $asset = assetModel::find($request->input('asset_id'));
        $user = Auth::user();

        $deptHead = User::where('usertype', 'dept_head')
                        ->where('dept_id', $user->dept_id)
                        ->first();

        if ($deptHead) {
            $notificationData = [
                'title' => 'New Maintenance Request',
                'message' => "Maintenance request for asset '{$asset->name}' (Code: {$asset->code})",
                'asset_name' => $asset->name,
                'asset_code' => $asset->code,
                'authorized_by' => $user->id,
                'authorized_user_name' => "{$user->firstname} {$user->lastname}",
                'action_url' => route('maintenance'),
            ];

            $deptHead->notify(new SystemNotification($notificationData));
        }


        // Redirect back with a success message
        return redirect()->back()->with('status', 'Maintenance request submitted successfully.');
    }

    public function cancelRequest($id)
    {
        // Find the maintenance request by its ID, or fail if not found
        $request = Maintenance::findOrFail($id);

        // Check if the request is pending; only pending requests can be canceled
        if ($request->status !== 'request') {
            return redirect()->back()->withErrors('Only requests can be canceled.');
        }

        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->back()->withErrors('You must be logged in to cancel a request.');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the authenticated user is allowed to cancel the request
        if ($user->id !== $request->requestor && $user->usertype !== 'admin') {
            return redirect()->back()->withErrors('You do not have permission to cancel this request.');
        }

        // Update the status to 'cancelled'
        $request->status = 'cancelled';
        $request->save();

        // Redirect back to the request list page or another relevant page with a success message
        return redirect()->route('requests.list')->with('status', 'Request canceled successfully.');
    }

    // QR SECTION
    public function uploadQRImage(Request $request)
    {
        // Validate the uploaded image
        $request->validate([
            'qr_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Store the uploaded image
        $imagePath = $request->file('qr_image')->store('qr_images', 'public');

        // Decode the QR code from the image
        $qrcode = new QrReader(storage_path('app/public/' . $imagePath));
        $text = $qrcode->text();

        // Redirect to asset details view
        return redirect()->route('qr.asset.details', ['code' => $text]);
    }

    // CHECK IF ASSET EXISTS
    public function checkAsset($code)
    {
        $asset = assetModel::where('code', $code)->first();

        if ($asset) {
            // Asset exists, return the asset details view
            return view('assetdetails', compact('asset'));
        } else {
            // Asset does not exist, return JSON response with an error
            return response()->json(['error' => 'Asset not found'], 404);
        }
    }

    //  ASSET DETAILS
    public function showDetails($code)
    {
        $userDept = Auth::user()->dept_id;
        $userType = Auth::user()->usertype;

        // Query the asset based on the scanned code and user's department
        $retrieveDataQuery = assetModel::where('asset.code', $code)
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->join('model', 'asset.model_key', '=', 'model.id')
            ->join('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->join('location', 'asset.loc_key', '=', 'location.id')
            ->leftJoin('maintenance', 'maintenance.asset_key', '=', 'asset.id') // Join with the maintenance table
            ->leftJoin('users', 'maintenance.authorized_by', '=', 'users.id') // Join with the users table to get approver details
            ->select(
                'asset.id',
                'asset.code',
                'asset.name',
                'asset.asst_img as image', // Use the correct column and alias it as 'image'
                'asset.purchase_cost as cost', // Use the correct column and alias it as 'cost'
                'asset.depreciation',
                'asset.salvage_value as salvageVal', // Adjusted to match the new column name
                'asset.usage_lifespan as usage_Lifespan', // Adjusted casing to match
                'asset.status',
                'asset.ctg_ID',
                'asset.dept_ID',
                'asset.manufacturer_key',
                'asset.model_key',
                'asset.loc_key',
                'asset.custom_fields',
                'asset.qr_img as qr', // Adjusted to use the correct QR image column
                'asset.created_at',
                'asset.updated_at',
                'category.name as category',
                'model.name as model',
                'location.name as location',
                'manufacturer.name as manufacturer',
                'department.name as department',
                'maintenance.reason', // Add the reason field
                'maintenance.status as request_status', // Add the status of the request
                'maintenance.authorized_at', // Add the authorized_at field
                'users.firstname as authorized_firstname',
                'users.middlename as authorized_middlename',
                'users.lastname as authorized_lastname',
                'department.name as authorized_department'
            );

        // If the user is not an admin, restrict by department
        if ($userType != 'admin') {
            $retrieveDataQuery->where('asset.dept_ID', '=', $userDept);
        }

        // Retrieve asset data
        $retrieveData = $retrieveDataQuery->first();

        // If asset not found, redirect back with error message
        if (!$retrieveData) {
            return redirect()->back()->with('status', 'No asset found with the scanned QR code.');
        }

        // Decode custom fields
        $fields = json_decode($retrieveData->custom_fields, true);

        // Pass the asset data, custom fields, reason, and request status to the view
        return view('user.assetDetail', compact('retrieveData', 'fields'));
    }



}
