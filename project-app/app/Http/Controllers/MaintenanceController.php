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
use App\Notifications\SystemNotification;
use Illuminate\Notifications\Notification as NotificationFacade; // Alias the facade
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    // Show the list of maintenance requests based on user type and tab
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'requests');
        $perPage = $request->input('rows_per_page', 10);

        // Default sorting for index: requested_at in ascending order
        $sortBy = $request->query('sort_by', 'maintenance.requested_at');
        $sortOrder = $request->query('sort_order', 'asc');

        $query = Maintenance::leftJoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->leftJoin('users', 'maintenance.requestor', '=', 'users.id')
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
            ->where('maintenance.status', 'request');

        if ($user->usertype === 'dept_head') {
            $query->where('asset.dept_ID', $user->dept_id);
        } elseif ($user->usertype === 'user') {
            $query->where('maintenance.requestor', $user->id);
        }

        $query->orderBy($sortBy, $sortOrder);

        $requests = $query->select(
            'maintenance.*',
            DB::raw("CONCAT(users.firstname, ' ', IFNULL(users.middlename, ''), ' ', users.lastname) AS requestor_name"),
            'category.name AS category_name',
            'location.name AS location_name',
            'asset.code AS asset_code'
        )->paginate($perPage);

        $view = $user->usertype === 'dept_head' ? 'dept_head.maintenance' :
                ($user->usertype === 'admin' ? 'admin.maintenance' : 'user.requestList');

        return view($view, compact('requests', 'tab', 'perPage', 'sortBy', 'sortOrder'));
    }


    public function approvedList(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('rows_per_page', 10);

        // Default sorting: updated_at in descending order
        $sortBy = $request->query('sort_by', 'maintenance.updated_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = Maintenance::leftJoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->leftJoin('users as requestor_user', 'maintenance.requestor', '=', 'requestor_user.id')
            ->leftJoin('users as authorized_user', 'maintenance.authorized_by', '=', 'authorized_user.id')
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->where('maintenance.status', 'approved')
            ->where('maintenance.is_completed', 0)
            ->orderBy($sortBy, $sortOrder);

        if ($user->usertype === 'dept_head') {
            $query->where('asset.dept_ID', $user->dept_id);
        }

        $requests = $query->select(
            'maintenance.*',
            DB::raw("CONCAT(requestor_user.firstname, ' ', IFNULL(requestor_user.middlename, ''), ' ', requestor_user.lastname) AS requestor_name"),
            DB::raw("CONCAT(authorized_user.firstname, ' ', IFNULL(authorized_user.middlename, ''), ' ', authorized_user.lastname) AS authorized_by_name"),
            'category.name AS category_name',
            'asset.code AS asset_code'
        )->paginate($perPage);

        $view = $user->usertype === 'dept_head' ? 'dept_head.maintenance' : 'admin.maintenance';

        return view($view, compact('requests', 'perPage', 'sortBy', 'sortOrder'))->with('tab', 'approved');
    }


    public function deniedList(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('rows_per_page', 10);

        // Default sorting: updated_at in descending order
        $sortBy = $request->query('sort_by', 'maintenance.updated_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = Maintenance::leftJoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->leftJoin('users as requestor_user', 'maintenance.requestor', '=', 'requestor_user.id')
            ->leftJoin('users as authorized_user', 'maintenance.authorized_by', '=', 'authorized_user.id')
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->where('maintenance.status', 'denied')
            ->orderBy($sortBy, $sortOrder);

        if ($user->usertype === 'dept_head') {
            $query->where('asset.dept_ID', $user->dept_id);
        }

        $requests = $query->select(
            'maintenance.*',
            DB::raw("CONCAT(requestor_user.firstname, ' ', IFNULL(requestor_user.middlename, ''), ' ', requestor_user.lastname) AS requestor_name"),
            DB::raw("CONCAT(authorized_user.firstname, ' ', IFNULL(authorized_user.middlename, ''), ' ', authorized_user.lastname) AS denied_by_name"),
            'category.name AS category_name',
            'asset.code AS asset_code'
        )->paginate($perPage);

        $view = $user->usertype === 'dept_head' ? 'dept_head.maintenance' : 'admin.maintenance';

        return view($view, compact('requests', 'perPage', 'sortBy', 'sortOrder'))->with('tab', 'denied');
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

        $maintenance->status = 'approved';
        $maintenance->authorized_by = $user->id;
        $maintenance->authorized_at = now();
        $maintenance->save();

        $asset->status = 'under_maintenance';
        $asset->save();

        // Set the authorized user details and timestamp
        $maintenance->authorized_by = $user->id;
        $maintenance->authorized_at = now(); // Set the authorized timestamp
        $maintenance->status = 'approved'; // Update request status to 'approved'
        $maintenance->save();

        Log::info('Maintenance request approved for asset ID: ' . $asset->id);

        // Generate the action URL for the request list
        $actionUrl = route('requests.list');

        $notificationData = [
            'title' => 'Maintenance Request Approved',
            'message' => "Your maintenance request for asset '{$asset->name}' (Code: {$asset->code}) has been approved.",
            'authorized_by' => $user->id,
            'authorized_user_name' => "{$user->firstname} {$user->lastname}",
            'asset_name' => $asset->name,
            'asset_code' => $asset->code,
            'action_url' => $actionUrl,  // Add the action URL
        ];

        $requestor = User::find($maintenance->requestor);

        if ($requestor) {
            $requestor->notify(new SystemNotification($notificationData));
            Log::info('Notification sent successfully to user ID: ' . $requestor->id);
        } else {
            Log::error('Requestor not found.');
        }

        // Log the approval activity
        ActivityLog::create([
            'activity' => 'Approve Maintenance Request',
            'description' => "Department Head {$user->firstname} {$user->lastname} approved the maintenance request (ID: {$maintenance->id}) for asset '{$asset->name}' (Code: {$asset->code}).",
            'userType' => $user->usertype, // 'dept_head'
            'user_id' => $user->id,
            'asset_id' => $asset->id,
            'request_id' => $maintenance->id,
        ]);

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

        $actionUrl = route('requests.list'); // Generate the request list URL

        $notificationData = [
            'title' => 'Maintenance Request Denied',
            'message' => "Your maintenance request for asset '{$asset->name}' (Code: {$asset->code}) has been denied. Reason: {$maintenance->reason}.",
            'authorized_by' => $user->id,
            'authorized_user_name' => "{$user->firstname} {$user->lastname}",
            'asset_name' => $asset->name,
            'asset_code' => $asset->code,
            'action_url' => $actionUrl,  // Add the action URL
        ];

        $requestor = User::find($maintenance->requestor);

        if ($requestor) {
            $requestor->notify(new SystemNotification($notificationData));
            Log::info('Notification sent successfully to user ID: ' . $requestor->id);
        } else {
            Log::error('Requestor not found.');
        }

        // Log the denial activity
        ActivityLog::create([
            'activity' => 'Deny Maintenance Request',
            'description' => "Department Head {$user->firstname} {$user->lastname} denied the maintenance request (ID: {$maintenance->id}) for asset '{$asset->name}' (Code: {$asset->code}). Reason: {$maintenance->reason}.",
            'userType' => $user->usertype, // 'dept_head'
            'user_id' => $user->id,
            'asset_id' => $asset->id,
            'request_id' => $maintenance->id,
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

    public function create()
    {
        // Get the currently authenticated user
        $user = Auth::user();
        $userType = $user->usertype;
        // dd($userType);
        // Only retrieve assets that belong to the same department as the user
        // $assets = assetModel::where('dept_ID', $user->dept_id)->get(['id', 'code', 'name']);
        $assets = assetModel::where('dept_ID', $user->dept_id)
                    ->where('isDeleted', 0) // Exclude deleted assets
                    ->get(['id', 'code', 'name']);

        // Retrieve categories, locations, models, manufacturers related to the user's department if applicable
        $categories = category::where('dept_ID', $user->dept_id)->get(['id', 'name']);
        $locations = locationModel::all(['id', 'name']); // No department link, fetching all
        $models = ModelAsset::all(['id', 'name']); // No department link, fetching all
        $manufacturers = Manufacturer::all(['id', 'name']); // No department link, fetching all

        $route = $userType === 'admin' ? 'admin.createmaintenance' : 'dept_head.createmaintenance';

        return view($route, compact('assets', 'categories', 'locations', 'models', 'manufacturers'));
    }

    public function getAssetDetails($id)
    {
        // Retrieve the asset details based on its id, including relationships
        $asset = assetModel::where('id', $id)
            ->with(['category', 'manufacturer', 'model', 'location'])
            ->first();

        if (!$asset) {
            return response()->json(['error' => 'Asset not found'], 404); // Error handling
        }

        // Prepare the image URL or set to "No Image" placeholder
        $asset->asst_img = $asset->asst_img
            ? asset('storage/' . $asset->asst_img)
            : asset('images/no-image.png');

        // Return the asset details as a custom JSON response
        return response()->json([
            'id' => $asset->id,
            'code' => $asset->code, // Asset code
            'name' => $asset->name, // Asset name
            'model' => $asset->model ? ['id' => $asset->model->id, 'name' => $asset->model->name] : null,
            'category' => $asset->category ? ['id' => $asset->category->id, 'name' => $asset->category->name] : null,
            'location' => $asset->location ? ['id' => $asset->location->id, 'name' => $asset->location->name] : null,
            'manufacturer' => $asset->manufacturer ? ['id' => $asset->manufacturer->id, 'name' => $asset->manufacturer->name] : null,
            'image_url' => $asset->asst_img // Image URL
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $userType = $user->usertype;

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
        // $frequencyDays = 0;
        // switch ($validatedData['frequency']) {
        //     case 'every_day':
        //         $frequencyDays = 1;
        //         break;
        //     case 'every_week':
        //         $frequencyDays = 7;
        //         break;
        //     case 'every_month':
        //         $frequencyDays = 30;
        //         break;
        //     case 'every_year':
        //         $frequencyDays = 365;
        //         break;
        //     case 'custom':
        //         if (isset($validatedData['repeat']) && isset($validatedData['interval'])) {
        //             $frequencyDays = $validatedData['repeat'] * $validatedData['interval'];
        //         } else {
        //             $frequencyDays = 1; // Set a default value if repeat or interval is null
        //         }
        //         break;
        // }

        // // Handle 'ends' logic correctly
        // if ($validatedData['ends'] === 'never') {
        //     $ends = 0; // Never ends
        // } else {
        //     $ends = (int)$validatedData['ends']; // Convert to integer for occurrences
        // }


        // Check if active preventive maintenance already exists for the asset
        $existingMaintenance = Preventive::where('asset_key', $validatedData['asset_code'])
                                        ->where('status', 'active')
                                        ->first();

        if ($existingMaintenance) {
            // Set session value for the error notification
            session()->flash('status', 'An active preventive maintenance already exists for this asset.');
            session()->flash('status_type', 'error'); // Set the status type for error

            return redirect()->back(); // Redirect back to the form
        }

        // Determine the frequency in days
        $frequencyDays = match ($validatedData['frequency']) {
            'every_day' => 1,
            'every_week' => 7,
            'every_month' => 30,
            'every_year' => 365,
            'custom' => ($validatedData['repeat'] ?? 1) * ($validatedData['interval'] ?? 1),
            default => 1,
        };

        $ends = $validatedData['ends'] === 'never' ? 0 : (int)$validatedData['ends'];

        // Insert the data into the preventive table
        Preventive::create([
            'asset_key' => $validatedData['asset_code'],  // Assuming asset_key is the asset ID
            'cost' => $validatedData['cost'],
            'frequency' => $frequencyDays,  // Frequency stored in days
            'ends' => $ends,  // 0 for "never", a number for occurrences
            'occurrences' => 0,  // Initialize with 0 occurrences
            'status' => 'active',  // Set initial status to active
            'next_maintenance_timestamp' => now()->addDays($frequencyDays)->timestamp,  // Store as Unix timestamp
        ]);

        // Retrieve asset and admin user for notification
        $asset = assetModel::find($validatedData['asset_code']);
        $admin = User::where('usertype', 'admin')->first();
        $deptHead = auth()->user(); // Get the logged-in department head

        // Prepare ends and occurrences message (if applicable)
        $endsMessage = $ends > 0 ? " up to {$ends} occurrence/s" : " with no end limit";

        // Check if admin exists to send the notification
        if ($admin) {
            $notificationData = [
                'title' => 'Preventive Maintenance Created',
                'message' => "Preventive maintenance for asset '{$asset->name}' (Code: {$asset->code}) is scheduled every {$frequencyDays} day/s{$endsMessage}",
                'asset_name' => $asset->name,
                'asset_code' => $asset->code,
                'authorized_by' => $deptHead->id,
                'authorized_user_name' => "{$deptHead->firstname} {$deptHead->lastname}",
                'action_url' => route('maintenance_sched'), // Link to the schedule page
            ];

            \Log::info('Sending preventive maintenance notification to admin.', [
                'admin_id' => $admin->id,
                'notification_data' => $notificationData,
            ]);

            // Send the notification to the admin
            $admin->notify(new SystemNotification($notificationData));
        } else {
            \Log::warning('No admin found to notify for the new preventive maintenance.');
        }

        // Log the activity
        ActivityLog::create([
            'activity' => 'Create Maintenance Schedule',
            'description' => "Department Head {$deptHead->firstname} {$deptHead->lastname} created a preventive maintenance schedule for asset '{$asset->name}' (Code: {$asset->code}).",
            'userType' => $deptHead->usertype, // 'dept_head'
            'user_id' => $deptHead->id,
            'asset_id' => $asset->id,
        ]);

        // Set session value for success notification
        session()->flash('status', 'Maintenance schedule created successfully!');
        // session()->flash('status_type', 'success'); // Set the status type for success

        // $route = $userType === 'admin'
        //     ? 'adminMaintenance_sched'
        //     : 'maintenance_sched';
        // return redirect()->route($route)->with('success', 'Maintenance schedule created successfully!');

            // Set session value for success notification
        return redirect()->route($userType === 'admin' ? 'adminMaintenance_sched' : 'maintenance_sched', ['dropdown' => 'open'])
        ->with([
            'status' => 'Maintenance schedule created successfully!',
            'status_type' => 'success'
        ]);
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
            'completion_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Find the maintenance request by ID
        $maintenance = Maintenance::findOrFail($id);

        // Determine if the request is marked as completed
        $isCompleted = $request->has('set_as_completed');
        $isCancelled = $request->has('set_as_cancelled');

        // Prevent both completed and cancelled from being set at the same time
        if ($isCompleted && $isCancelled) {
            return redirect()->back()->withErrors(['error' => 'Maintenance cannot be both completed and cancelled.']);
        }

        // Set status to 'cancelled' only if cancelled is checked
        $status = $isCancelled ? 'cancelled' : $maintenance->status;

        // Update the maintenance details
        $maintenance->update([
            'type' => $request->type,
            'start_date' => $request->start_date,
            'cost' => $request->cost,
            'is_completed' => $isCompleted, // Boolean handling
            'status' => $status, // Only change status to 'cancelled' if applicable
            'completion_date' => $isCompleted ? now() : null,
            'authorized_at' => now(), // Update the authorized_at field
        ]);

        //trigger predictive maintenance when an approved request is set as completed (checkbox)
        if ($isCompleted) {
            // Trigger the predictive analysis directly by calling the analyze() method
            $predictiveController = new \App\Http\Controllers\PredictiveController();
            $predictiveController->analyze(); // Run the analysis directly
            \Log::info('Predictive analysis triggered directly after completion.');
        }

        $statusMessage = $isCancelled
        ? 'Maintenance request cancelled successfully.'
        : 'Maintenance request updated successfully.';

        // Redirect back with success message
        return redirect()->route('maintenance.approved')
            ->with('status', $statusMessage);
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
        // Validate that the status and reason are provided
        $request->validate([
            'status' => 'required|string|in:approved,denied',
            'reason' => 'nullable|string|max:255',
        ]);

        // Find the maintenance request by ID
        $maintenance = Maintenance::findOrFail($id);

        // Get associated asset
        $asset = assetModel::findOrFail($maintenance->asset_key);
        // Get the department head (logged-in user)
        $deptHead = Auth::user();

        // Update the status, authorized_at, and reason
        $maintenance->update([
            'status' => $request->status,
            'authorized_at' => now(),
            'reason' => $request->reason, // Update the reason field
        ]);

        $requestor = User::find($maintenance->requestor);

        if ($requestor) {
            // Prepare notification data
            $notificationData = [
                'title' => 'Maintenance Request Status Updated',
                'message' => "Your maintenance request for asset '{$asset->name}' (Code: {$asset->code}) has been updated to '{$request->status}'.",
                'authorized_by' => $deptHead->id,
                'authorized_user_name' => "{$deptHead->firstname} {$deptHead->lastname}",
                'asset_name' => $asset->name,
                'asset_code' => $asset->code,
                'action_url' => route('requests.list'), // URL to view the request list
            ];

            // Send the notification
            $requestor->notify(new SystemNotification($notificationData));
            Log::info('Notification sent to user ID: ' . $requestor->id, ['notification_data' => $notificationData]);
        } else {
            Log::warning('Requestor not found for maintenance request ID: ' . $id);
        }

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

    public function showRecords(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'completed'); // Default tab is 'completed'
        $searchQuery = $request->input('query', '');
        $perPage = $request->input('rows_per_page', 10); // Default rows per page is 10

        $query = Maintenance::leftJoin('asset', 'maintenance.asset_key', '=', 'asset.id')
            ->leftJoin('users', 'maintenance.requestor', '=', 'users.id')
            ->leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->leftJoin('location', 'asset.loc_key', '=', 'location.id')
            ->select(
                'maintenance.*',
                DB::raw("CONCAT(users.firstname, ' ', IFNULL(users.middlename, ''), ' ', users.lastname) AS requestor_name"),
                'category.name AS category_name',
                'location.name AS location_name',
                'asset.code as asset_code'
            );

        // Apply filters based on the selected tab
        if ($tab === 'completed') {
            $query->where('maintenance.status', 'approved')
                ->where('maintenance.is_completed', 1)
                ->orderBy('maintenance.updated_at', 'desc');
        } elseif ($tab === 'cancelled') {
            $query->where('maintenance.status', 'cancelled')
                ->orderBy('maintenance.updated_at', 'desc');
        }

        // Apply department filter if the user is a department head
        if ($user->usertype === 'dept_head') {
            $query->where('asset.dept_ID', $user->dept_id);
        } elseif ($user->usertype === 'user') {
            $query->where('maintenance.requestor', $user->id);
        }

        // Apply search filter if a query is provided
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
        $records = $query->paginate($perPage);

        // Return the appropriate view based on the user type
        $view = $user->usertype === 'admin' ? 'admin.records' : 'dept_head.maintenance_records';

        return view($view, [
            'records' => $records,
            'tab' => $tab,
            'searchQuery' => $searchQuery,
            'perPage' => $perPage,
        ]);
    }
}
