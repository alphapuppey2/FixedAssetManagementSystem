<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\assetModel;
use App\Models\category;
use App\Models\locationModel;
use App\Models\ModelAsset;
use App\Models\Manufacturer;
use App\Models\Preventive;
use App\Jobs\RunPredictiveAnalysis;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;


class MaintenanceController extends Controller
{
    // Show the list of maintenance requests based on user type and tab
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'requests'); // Default tab is 'requests'
        $searchQuery = $request->input('query', '');
        $perPage = $request->input('rows_per_page', 10); // Default rows per page is 10

        $query = Maintenance::leftjoin('asset', 'maintenance.asset_key', '=', 'asset.id');

        // Apply status filter based on the selected tab
        if ($tab === 'requests') {
            $query->where('maintenance.status', 'request');
        } elseif ($tab === 'approved') {
            $query->where('maintenance.status', 'approved');
        } elseif ($tab === 'denied') {
            $query->where('maintenance.status', 'denied');
        }

        // Apply department filter for department heads
        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;
            $query->where('asset.dept_ID', $deptId);
        } elseif ($user->usertype === 'user') {
            $query->where('maintenance.requestor', $user->id);
        }

        // Apply search filter
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('maintenance.id', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('users.firstname', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('users.middlename', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('users.lastname', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('maintenance.description', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('asset.code', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('category.name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('location.name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('maintenance.type', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('maintenance.reason', 'LIKE', "%{$searchQuery}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.requested_at, '%Y-%m-%d')"), 'LIKE', "%{$searchQuery}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.authorized_at, '%Y-%m-%d')"), 'LIKE', "%{$searchQuery}%");
            });
        }

        // Fetch the filtered and paginated results
        $requests = $query->leftjoin('users', 'maintenance.requestor', '=', 'users.id')
            ->leftjoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftjoin('location', 'asset.loc_key', '=', 'location.id')
            ->select(
                'maintenance.*',
                DB::raw("CONCAT(users.firstname, ' ', IFNULL(users.middlename, ''), ' ', users.lastname) AS requestor_name"),
                'category.name AS category_name',
                'location.name AS location_name',
                'asset.code as asset_code'
            )
            ->paginate($perPage);

        // Return the view with the filtered requests and selected tab
        if ($user->usertype === 'dept_head') {
            return view('dept_head.maintenance', [
                'requests' => $requests,
                'tab' => $tab,
                'searchQuery' => $searchQuery,
                'perPage' => $perPage,
            ]);
        } elseif ($user->usertype === 'admin') {
            return view('admin.maintenance', [
                'requests' => $requests,
                'tab' => $tab,
                'searchQuery' => $searchQuery,
                'perPage' => $perPage,
            ]);
        } else {
            return view('user.requestList', [
                'requests' => $requests,
                'searchQuery' => $searchQuery,
                'perPage' => $perPage,
            ]);
        }
    }


    // Search functionality
    public function search(Request $request)
    {
        return $this->index($request);
    }

    // Show the list of maintenance requests for the department head
    public function requests(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('rows_per_page', 10);

        $query = Maintenance::leftjoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->where('maintenance.status', 'request')
            ->select('maintenance.*');

        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;
            $query->where('asset.dept_ID', $deptId);
        } else {
            return redirect()->route('user.home');
        }

        // $requests = $query->get();
        $requests = $query->leftjoin('users', 'maintenance.requestor', '=', 'users.id')
            ->leftjoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftjoin('location', 'asset.loc_key', '=', 'location.id')
            ->select('maintenance.*', DB::raw("CONCAT(users.firstname, ' ', IFNULL(users.middlename, ''), ' ', users.lastname) AS requestor_name"), 'category.name AS category_name', 'location.name AS location_name', 'asset.code as asset_code')
            ->paginate($perPage);

        return view('dept_head.maintenance', [
            'requests' => $requests,
            'tab' => 'requests',
            'perPage' => $perPage,
        ]);
    }

    // Show the list of approved maintenance requests
    public function approved(Request $request)
    {
        $user = Auth::user();
        $searchQuery = ''; // Initialize to empty string
        $perPage = $request->input('rows_per_page', 10);

        $query = Maintenance::leftjoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->where('maintenance.status', 'approved')
            ->select('maintenance.*');

        // Apply department filter for department heads
        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;
            $query->where('asset.dept_ID', $deptId);
        } elseif ($user->usertype !== 'admin') {
            // Redirect non-admin and non-dept_head users
            return redirect()->route('user.home');
        }

        // Apply joins and select relevant columns
        $requests = $query->leftjoin('users as requestor_user', 'maintenance.requestor', '=', 'requestor_user.id')
            ->leftjoin('users as authorized_user', 'maintenance.authorized_by', '=', 'authorized_user.id')
            ->leftjoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->select(
                'maintenance.*',
                DB::raw("CONCAT(requestor_user.firstname, ' ', IFNULL(requestor_user.middlename, ''), ' ', requestor_user.lastname) AS requestor_name"),
                DB::raw("CONCAT(authorized_user.firstname, ' ', IFNULL(authorized_user.middlename, ''), ' ', authorized_user.lastname) AS authorized_by_name"),
                'category.name AS category_name',
                'asset.code as asset_code'
            )
            ->paginate($perPage);

        // Return the appropriate view for the user's role
        if ($user->usertype === 'dept_head') {
            return view('dept_head.maintenance', [
                'requests' => $requests,
                'tab' => 'approved',
                'searchQuery' => $searchQuery,
                'perPage' => $perPage, // Passing an empty search query
            ]);
        } elseif ($user->usertype === 'admin') {
            return view('admin.maintenance', [
                'requests' => $requests,
                'tab' => 'approved',
                'searchQuery' => $searchQuery,
                'perPage' => $perPage, // Passing an empty search query
            ]);
        }

        // Fallback redirect for other user types
        return redirect()->route('user.home');
    }


    // Show the list of denied maintenance requests

    public function denied(Request $request)
    {
        $user = Auth::user();
        $searchQuery = ''; // Initialize to empty string
        $perPage = $request->input('rows_per_page', 10);

        $query = Maintenance::leftjoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->where('maintenance.status', 'denied')
            ->select('maintenance.*');

        // Apply department filter for department heads
        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;
            $query->where('asset.dept_ID', $deptId);
        } elseif ($user->usertype !== 'admin') {
            // Redirect non-admin and non-dept_head users
            return redirect()->route('user.home');
        }

        // Apply joins and select relevant columns
        $requests = $query->leftjoin('users as requestor_user', 'maintenance.requestor', '=', 'requestor_user.id')
            ->leftjoin('users as authorized_user', 'maintenance.authorized_by', '=', 'authorized_user.id')
            ->leftjoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->select(
                'maintenance.*',
                DB::raw("CONCAT(requestor_user.firstname, ' ', IFNULL(requestor_user.middlename, ''), ' ', requestor_user.lastname) AS requestor_name"),
                DB::raw("CONCAT(authorized_user.firstname, ' ', IFNULL(authorized_user.middlename, ''), ' ', authorized_user.lastname) AS denied_by_name"),
                'category.name AS category_name',
                'asset.code as asset_code'
            )
            ->paginate($perPage);

        // Return the appropriate view for the user's role
        if ($user->usertype === 'dept_head') {
            return view('dept_head.maintenance', [
                'requests' => $requests,
                'tab' => 'denied',
                'searchQuery' => $searchQuery,
                'perPage' => $perPage, // Passing an empty search query
            ]);
        } elseif ($user->usertype === 'admin') {
            return view('admin.maintenance', [
                'requests' => $requests,
                'tab' => 'denied',
                'searchQuery' => $searchQuery,
                'perPage' => $perPage, // Passing an empty search query
            ]);
        }

        // Fallback redirect for other user types
        return redirect()->route('user.home');
    }


    // Approve a maintenance request
    public function approve($id)
    {
        $user = Auth::user();

        Log::info('Approving request with user ID: ' . $user->id);

        // Ensure the user is a department head
        if ($user->usertype !== 'dept_head') {
            return redirect()->route('user.home');
        }

        // Find the maintenance request
        $maintenance = Maintenance::findOrFail($id);
        $asset = assetModel::findOrFail($maintenance->asset_key);

        $asset->status = "under_maintenance";
        $asset->save();

        // Update the status to 'approved'
        $maintenance->status = 'approved';
        $maintenance->authorized_by = $user->id;
        $maintenance->authorized_at = now();
        $maintenance->save();

        // Notify the user about the approval
        $notification = Notification::create([
            'user_id' => $maintenance->requestor,  // Assuming the requestor field stores the user ID
            'title' => 'Maintenance Request Approved',
            'message' => "Your maintenance request for asset '{$asset->name}' (Code: {$asset->code}) has been approved.",
            'status' => 'unread',
            'is_deleted' => 0,
            'authorized_by' => $user->id,
        ]);

        Log::info('Notification created: ' . json_encode($notification));

        return redirect()->route('maintenance')->with('status', 'Request approved successfully.');
    }

    // Deny a maintenance request
    public function deny(Request $request, $id)
    {
        $user = Auth::user();

        // Ensure the user is a department head
        if ($user->usertype !== 'dept_head') {
            return redirect()->route('user.home');
        }

        // Find the maintenance request
        $maintenance = Maintenance::findOrFail($id);
        $asset = assetModel::findOrFail($maintenance->asset_key); // Get the associated asset

        // Validate the reason
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Update the status to 'denied'
        $maintenance->status = 'denied';
        $maintenance->authorized_by = $user->id;
        $maintenance->authorized_at = now();
        $maintenance->reason = $request->input('reason');
        $maintenance->save();

        // Notify the user about the denial
        Notification::create([
            'user_id' => $maintenance->requestor,  // Assuming the requestor field stores the user ID
            'title' => 'Maintenance Request Denied',
            'message' => "Your maintenance request for asset '{$asset->name}' (Code: {$asset->code}) has been denied. Reason: ". $request->input('reason'),
            'status' => 'unread',
            'is_deleted' => 0,
            'authorized_by' => $user->id,
        ]);

        return redirect()->route('maintenance')->with('status', 'Request denied.');
    }

    //download button
    public function download(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'requests'); // Default tab is 'requests'
        $searchQuery = $request->input('query', ''); // Default to empty string if no search query

        $query = Maintenance::join('asset', 'maintenance.asset_key', '=', 'asset.id');

        // Apply status filter based on the selected tab
        if ($tab === 'requests') {
            $query->where('maintenance.status', 'request');
        } elseif ($tab === 'approved') {
            $query->where('maintenance.status', 'approved');
        } elseif ($tab === 'denied') {
            $query->where('maintenance.status', 'denied');
        }

        // Apply department filter for department heads
        if ($user->usertype === 'dept_head') {
            $deptId = $user->dept_id;
            $query->where('asset.dept_ID', $deptId);
        } elseif ($user->usertype === 'user') {
            $query->where('maintenance.requestor', $user->id);
        } else {
            return redirect()->route('user.home');
        }

        // Apply search filter
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('maintenance.id', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('users.firstname', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('users.middlename', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('users.lastname', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('maintenance.description', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('maintenance.repair', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('maintenance.reason', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('asset.code', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('category.name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('location.name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.requested_at, '%Y-%m-%d')"), 'LIKE', "%{$searchQuery}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.authorized_at, '%Y-%m-%d')"), 'LIKE', "%{$searchQuery}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.created_at, '%Y-%m-%d')"), 'LIKE', "%{$searchQuery}%")
                    ->orWhere(DB::raw("DATE_FORMAT(maintenance.updated_at, '%Y-%m-%d')"), 'LIKE', "%{$searchQuery}%");
            });
        }

        // Fetch the data
        $maintenances = $query->join('users', 'maintenance.requestor', '=', 'users.id')
            ->leftJoin('users as authorized_user', 'maintenance.authorized_by', '=', 'authorized_user.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->join('location', 'asset.loc_key', '=', 'location.id')
            ->select(
                'maintenance.id',
                DB::raw("CONCAT(users.firstname, ' ', IFNULL(users.middlename, ''), ' ', users.lastname) AS requestor_name"),
                'maintenance.asset_key',
                'maintenance.description',
                'category.name AS category_name',
                'location.name AS location_name',
                'maintenance.status',
                'maintenance.type',
                'asset.code as asset_code',
                'maintenance.reason',
                DB::raw("DATE_FORMAT(maintenance.requested_at, '%Y-%m-%d') as requested_at"),
                DB::raw("DATE_FORMAT(maintenance.authorized_at, '%Y-%m-%d') as authorized_at"),
                DB::raw("DATE_FORMAT(maintenance.created_at, '%Y-%m-%d %H:%i:%s') as created_at"),
                DB::raw("DATE_FORMAT(maintenance.updated_at, '%Y-%m-%d %H:%i:%s') as updated_at"),
                DB::raw("CONCAT(authorized_user.firstname, ' ', IFNULL(authorized_user.middlename, ''), ' ', authorized_user.lastname) AS authorized_by_name")
            )
            ->get();

        // Generate CSV content
        $csvContent = "Request ID,Requestor,Asset Code,Description,Category,Location,Status,Type,Reason,Requested At,Authorized At,Created At,Updated At,Authorized By\n";
        foreach ($maintenances as $maintenance) {
            $csvContent .= "\"{$maintenance->id}\",\"{$maintenance->requestor_name}\",\"{$maintenance->asset_code}\",\"{$maintenance->description}\",\"{$maintenance->category_name}\",\"{$maintenance->location_name}\",\"{$maintenance->status}\",\"{$maintenance->type}\",\"{$maintenance->reason}\",\"{$maintenance->requested_at}\",\"{$maintenance->authorized_at}\",\"{$maintenance->created_at}\",\"{$maintenance->updated_at}\",\"{$maintenance->authorized_by_name}\"\n";
        }

        // Return response as a CSV file download
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="maintenance_' . $tab . '_' . now()->format('Y-m-d_H:i:s') . '.csv"');
    }


    // public function create() {
    //     $assets = assetModel::all(['id', 'code', 'name']); // Retrieve asset id, code, and name
    //     return view('dept_head.createmaintenance', compact('assets'));
    // }

    // public function create() {
    //     $assets = assetModel::all(['id', 'code', 'name']); // Retrieve asset id, code, and name
    //     $categories = category::all(['id', 'name']);       // Retrieve category id and name
    //     $locations = locationModel::all(['id', 'name']);        // Retrieve location id and name
    //     $models = ModelAsset::all(['id', 'name']);              // Retrieve model id and name
    //     $manufacturers = Manufacturer::all(['id', 'name']); // Retrieve manufacturer id and name

    //     return view('dept_head.createmaintenance', compact('assets', 'categories', 'locations', 'models', 'manufacturers'));
    // }

    public function create()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Only retrieve assets that belong to the same department as the user
        $assets = assetModel::where('dept_ID', $user->dept_id)->get(['id', 'code', 'name']);

        // Retrieve categories, locations, models, manufacturers related to the user's department if applicable
        $categories = category::where('dept_ID', $user->dept_id)->get(['id', 'name']);
        $locations = locationModel::all(['id', 'name']); // No department link, fetching all
        $models = ModelAsset::all(['id', 'name']); // No department link, fetching all
        $manufacturers = Manufacturer::all(['id', 'name']); // No department link, fetching all

        return view('dept_head.createmaintenance', compact('assets', 'categories', 'locations', 'models', 'manufacturers'));
    }

    // public function getAssetDetails($id) {
    //     // Retrieve the asset details based on its id
    //     $asset = assetModel::where('id', $id)->with(['category', 'manufacturer', 'model', 'location'])->first();

    //     // Prepare the image URL or set to "No Image" placeholder
    //     $asset->image_url = $asset->image ? asset('storage/' . $asset->image) : asset('images/no-image.png');

    //     // Return the asset details as JSON
    //     return response()->json($asset);
    // }
    public function getAssetDetails($id)
    {
        // Retrieve the asset details based on its id, including relationships
        $asset = assetModel::where('id', $id)->with(['category', 'manufacturer', 'model', 'location'])->first();

        if (!$asset) {
            return response()->json(['error' => 'Asset not found'], 404); // Error handling
        }

        // Prepare the image URL or set to "No Image" placeholder
        $asset->image_url = $asset->image ? asset('storage/' . $asset->image) : asset('images/no-image.png');

        // Return the asset details as a custom JSON response
        return response()->json([
            'id' => $asset->id,
            'code' => $asset->code, // Asset code
            'name' => $asset->name, // Asset name
            'model' => $asset->model ? ['id' => $asset->model->id, 'name' => $asset->model->name] : null,
            'category' => $asset->category ? ['id' => $asset->category->id, 'name' => $asset->category->name] : null,
            'location' => $asset->location ? ['id' => $asset->location->id, 'name' => $asset->location->name] : null,
            'manufacturer' => $asset->manufacturer ? ['id' => $asset->manufacturer->id, 'name' => $asset->manufacturer->name] : null,
            'image_url' => $asset->image_url // Image URL
        ]);
    }

    public function store(Request $request)
    {
        // Validate the form input
        $validatedData = $request->validate([
            'asset_code' => 'required|exists:asset,id',
            'cost' => 'required|numeric',
            'frequency' => 'required|string',
            'repeat' => 'nullable|integer',
            'interval' => 'nullable|integer',
            'ends' => ['required', 'regex:/^(never|\d+)$/'],  // Allow "never" or a numeric value
            'occurrence' => 'nullable|integer', // For custom occurrences
        ]);

        // Determine the frequency in days
        $frequencyDays = 0;
        switch ($validatedData['frequency']) {
            case 'every_day':
                $frequencyDays = 1;
                break;
            case 'every_week':
                $frequencyDays = 7;
                break;
            case 'every_month':
                $frequencyDays = 30;
                break;
            case 'every_year':
                $frequencyDays = 365;
                break;
            case 'custom':
                if (isset($validatedData['repeat']) && isset($validatedData['interval'])) {
                    $frequencyDays = $validatedData['repeat'] * $validatedData['interval'];
                } else {
                    $frequencyDays = 1; // Set a default value if repeat or interval is null
                }
                break;
        }

        // Handle 'ends' logic correctly
        if ($validatedData['ends'] === 'never') {
            $ends = 0; // Never ends
        } else {
            $ends = (int)$validatedData['ends']; // Convert to integer for occurrences
        }


        // Insert the data into the preventive table
        Preventive::create([
            'asset_key' => $validatedData['asset_code'],  // Assuming asset_key is the asset ID
            'cost' => $validatedData['cost'],
            'frequency' => $frequencyDays,  // Frequency stored in days
            'ends' => $ends,  // 0 for "never", a number for occurrences
        ]);

        // Set session value for success notification
        session()->flash('status', 'Maintenance schedule created successfully!');

        return redirect()->route('maintenance_sched')->with('success', 'Maintenance schedule created successfully!');
    }

    // MaintenanceController.php
    public function editApproved($id)
    {
        // Load related asset, category, location, model, and manufacturer data
        $maintenance = Maintenance::with(['asset', 'category', 'location', 'model', 'manufacturer'])
            ->findOrFail($id);

        return view('dept_head.modal.editApprove', compact('maintenance'));
    }

    // In your MaintenanceController updateApproved function
    public function updateApproved(Request $request, $id)
    {

        $request->validate([
            'cost' => 'required|numeric|min:0',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'completion_date' => 'nullable|date',
        ]);

        // Find the maintenance request by ID
        $maintenance = Maintenance::findOrFail($id);

        // Update the maintenance details
        $maintenance->update([
            'type' => $request->type,
            'start_date' => $request->start_date,
            'cost' => $request->cost,
            'completed' => $request->has('set_as_completed'),
            // 'completion_date' => $request->completion_date,
            'completion_date' => $request->has('set_as_completed') ? now() : null,

        ]);

        // Redirect back with success message
        return redirect()->route('maintenance.approved')
            ->with('status', 'Maintenance request updated successfully.');
    }

    public function editDenied($id)
    {
        // Load related asset, category, location, model, and manufacturer data
        $maintenance = Maintenance::with(['asset', 'category', 'location', 'model', 'manufacturer'])
            ->findOrFail($id);

        return view('dept_head.modal.editDenied', compact('maintenance'));
    }

    public function updateDenied(Request $request, $id)
    {
        // Validate that the status is 'approved' or 'denied' (as per your dropdown in editDenied.blade.php)
        $request->validate([
            'status' => 'required|string|in:approved,denied',
        ]);

        // Find the maintenance request by ID
        $maintenance = Maintenance::findOrFail($id);

        // Update only the status
        $maintenance->update([
            'status' => $request->status,
        ]);

        // Redirect back with success message
        return redirect()->route('maintenance.denied')
            ->with('status', 'Maintenance request status updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $assetKey = $request->input('asset_key');
        $status = $request->input('status');

        // Update the status in the database
        Preventive::where('asset_key', $assetKey)->update(['status' => $status]);

        return response()->json(['message' => 'Status updated successfully']);
    }
}
