<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\department;
use App\Models\Maintenance;
use App\Models\assetModel;
use App\Models\AssignedToUser;
use App\Models\category;
use App\Models\locationModel;
use App\Models\Manufacturer;
use App\Models\ModelAsset;
use App\Models\User;
use App\Models\ActivityLog;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\SystemNotification;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AsstController extends Controller
{
     public function showAllAssets(Request $request)
        {
            // Fetch all categories for the filter modal
            $categoriesList = DB::table('category')->get();

            // Get department ID from the request (null if not provided)
            $deptId = $request->input('dept', null);

            // Get sorting parameters (default to 'asset.name' and 'asc')
            $sortBy = $request->input('sort_by', 'asset.name');
            $sortOrder = strtolower($request->input('sort_order', 'asc'));

            // Validate sort field and order
            $validSortFields = [
                'asset.name', 'asset.code', 'category.name',
                'department.name', 'asset.depreciation', 'asset.status'
            ];

            if (!in_array($sortBy, $validSortFields)) {
                $sortBy = 'asset.name';
            }
            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortOrder = 'asc';
            }

            // Get rows per page (default to 10)
            $perPage = max((int) $request->input('perPage', 10), 10);

            // Get search query from request
            $query = $request->input('query', '');

            // Get filter parameters (status, category, date range)
            $statuses = $request->input('status', []);
            $categories = $request->input('category', []);
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Build the query with department, search, and filters
            $assetsQuery = DB::table('asset')
                ->join('department', 'asset.dept_ID', '=', 'department.id')
                ->join('category', 'asset.ctg_ID', '=', 'category.id')
                ->select('asset.*', 'department.name as department', 'category.name as category')
                ->where('asset.isDeleted', 0)  // Exclude deleted assets
                ->when($deptId, fn($q) => $q->where('asset.dept_ID', $deptId))
                ->when($query !== '', fn($q) => $q->where(function ($subquery) use ($query) {
                    $subquery->where('asset.name', 'like', '%' . $query . '%')
                             ->orWhere('asset.code', 'like', '%' . $query . '%')
                             ->orWhere('category.name', 'like', '%' . $query . '%')
                             ->orWhere('department.name', 'like', '%' . $query . '%');
                }))
                ->when(!empty($statuses), fn($q) => $q->whereIn('asset.status', $statuses))
                ->when(!empty($categories), fn($q) => $q->whereIn('category.id', $categories))
                ->when($startDate && $endDate, fn($q) => $q->whereBetween('asset.created_at', [$startDate, $endDate]));

            // Apply sorting and paginate results
            $assets = $assetsQuery
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage)
                ->appends($request->all());  // Preserve query parameters

            // Return the view with the asset list and parameters
            return view('admin.assetList', compact('assets', 'sortBy', 'sortOrder', 'perPage', 'deptId', 'categoriesList'));
        }





    public function showDeptAsset(Request $request)
    {
        $userDept = Auth::user()->dept_id;

        // Get query parameters with defaults
        $search = $request->input('search');
        $sortField = $request->input('sort', 'code');
        $sortDirection = $request->input('direction', 'asc');
        $rowsPerPage = $request->input('rows_per_page', 10); // Default to 10 rows per page

        // Ensure statuses and categories are always arrays
        $statuses = $request->input('status', []);
        $categories = $request->input('category', []);

        if (is_string($statuses)) {
            $statuses = json_decode($statuses, true) ?? [];  // Decode JSON if it's a string
        }

        if (is_string($categories)) {
            $categories = json_decode($categories, true) ?? [];  // Decode JSON if it's a string
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validate sorting field
        $validSortFields = ['code', 'name', 'category_name', 'status'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'code';
        }

        // Build the query with filters
        $assets = DB::table('asset')
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->where('asset.dept_ID', $userDept)
            ->where('asset.isDeleted', 0)
            ->when($search, function ($query, $search) {
                return $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('asset.name', 'like', "%{$search}%")
                             ->orWhere('asset.code', 'like', "%{$search}%");
                });
            })
            ->when(!empty($statuses), function ($query) use ($statuses) {
                return $query->whereIn('asset.status', $statuses);
            })
            ->when(!empty($categories), function ($query) use ($categories) {
                return $query->whereIn('category.id', $categories);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('asset.created_at', [$startDate, $endDate]);
            })
            ->select('asset.*', 'category.name as category_name', 'department.name as department')
            ->orderBy($sortField, $sortDirection)
            ->paginate($rowsPerPage) // Handle pagination
            ->appends($request->except('page')); // Retain query parameters on pagination

        // Fetch all categories for the dropdown (filtered by department)
        $categoriesList = DB::table('category')->where('dept_ID', $userDept)->get();

        // Return the view with the necessary data
        return view('dept_head.asset', compact('assets', 'categoriesList'));
    }









        // ->orderByRaw("
        //     CASE
        //     WHEN asset.status = 'active' THEN 0
        //     WHEN asset.status = 'under_maintenance' THEN 1
        //     WHEN asset.status = 'deployed' THEN 2
        //     WHEN asset.status = 'disposed' THEN 3
        //     ELSE 4
        //     END
        //     ") //hello


    //Maintenance History of the
    public function showHistory($id)
    {
        //history of a Asset
        $asset = AssetModel::where('asset.id', $id)
            ->select("asset.code as assetCode")->first();

        $assetRet = Maintenance::where("asset_key", $id)
            ->where("is_completed", 1)
            ->join('users', 'users.id', '=', 'maintenance.requestor')
            ->select(
                'maintenance.reason as reason',
                'maintenance.type as type',
                'maintenance.cost as cost',
                'maintenance.description as description',
                DB::raw('DATE(maintenance.completion_date) AS complete',),
                'maintenance.status as status',
                'users.firstname as fname',
                'users.lastname as lname',
            )->groupBy(
                'maintenance.completion_date',
                'maintenance.status',
                'maintenance.type',
                'maintenance.cost',
                'users.firstname',
                'users.lastname',
                'maintenance.description',
                'maintenance.reason',
            )
            ->orderByRaw("FIELD(maintenance.status, 'request', 'pending', 'in_progress','complete','denied','denied')")
            ->orderBy('maintenance.completion_date', 'asc')
            ->get();
        $AssetMaintenance = Maintenance::where("asset_key", $id)->get();

        return view('dept_head.MaintenanceHistory', compact('assetRet', 'asset'));
    }

    public function showForm()
{
    $userRole = Auth::user()->usertype;
    $departments = department::all(); // Get all departments for admin dropdown
    $defaultDeptId = $departments->first()->id ?? null; // Set the first department as the default
    $usrDPT = $userRole === 'admin' ? $defaultDeptId : Auth::user()->dept_id;

    // Retrieve department details
    $department = department::find($usrDPT);
    if (!$department) {
        return redirect()->back()->with('noSettings', 'Department not found.');
    }

    // Fetch related data based on department
    $categories = ['ctglist' => DB::table('category')->where('dept_ID', $usrDPT)->get()];
    $location = ['locs' => DB::table('location')->where('dept_ID', $usrDPT)->get()];
    $model = ['mod' => DB::table('model')->where('dept_ID', $usrDPT)->get()];
    $manufacturer = ['mcft' => DB::table('manufacturer')->where('dept_ID', $usrDPT)->get()];
    $addInfos = json_decode($department->custom_fields);

    // Check if any of the settings are empty
    if (
        $categories['ctglist']->isEmpty() ||
        $location['locs']->isEmpty() ||
        $model['mod']->isEmpty() ||
        $manufacturer['mcft']->isEmpty()
    ) {
        return redirect()->back()->with('noSettings', 'Some settings are missing. Please set up your settings.');
    }

    if($userRole === 'admin'){
        $view = 'admin.createAsset';
        $compactContent = compact(
            'addInfos',
            'categories',
            'location',
            'model',
            'manufacturer',
            'departments', // Pass all departments for the dropdown
            'usrDPT',
        );
    }
    else{
        $view = 'dept_head.createAsset';
        $compactContent = compact(
            'addInfos',
            'categories',
            'location',
            'model',
            'manufacturer',
            'usrDPT',
        );
    }

    return view($view, $compactContent);
}

public function fetchDepartmentData($id)
{
    $categories = Category::where('dept_ID', $id)->get();
    $locations = locationModel::where('dept_ID', $id)->get();
    $models = ModelAsset::where('dept_ID', $id)->get();
    $manufacturers = Manufacturer::where('dept_ID', $id)->get();
    $department =  department::findOrFail($id);
    $addInfos = json_decode($department->custom_fields);
    return response()->json([
        'categories' => $categories,
        'locations' => $locations,
        'models' => $models,
        'manufacturers' => $manufacturers,
        'addInfos' => $addInfos
    ]);
}


    public  function convertJSON($key, $value)
    {
        $additionalInfo = [];
        // Initialize an empty array to hold key-value pairs
        if (isset($key) && isset($value)) {
            foreach ($key as $index => $keys) {
                if (!empty($key) && !empty($value[$index])) {
                    $additionalInfo[$keys] = $value[$index];
                }
            }
        }
        return json_encode($additionalInfo);
    }

    public function create(Request $request)
{
    $userDept = Auth::user()->dept_id;
    $deptHead = Auth::user();
    $isAdmin = $deptHead->usertype === 'admin'; // Check if the user is admin

    // Validate the request
    $request->validate([
        'asst_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif',
        'assetname' => 'required',
        'category' => 'required',
        'department' => $isAdmin ? 'required|exists:department,id' : 'nullable',
        'purchasedDate' => 'nullable|date|before_or_equal:today',
        'pCost' => 'required|numeric|min:0.01',
        'lifespan' => 'required|integer|min:0',
        'salvageValue' => 'required|numeric|min:0.01|lt:pCost',
        'depreciation' => 'required|numeric|min:0.01',
        'loc' => 'required|exists:location,id',
        'mod' => 'required|exists:model,id',
        'mcft' => 'required|exists:manufacturer,id',
        'field.key.*' => 'nullable|string|max:255',
        'field.value.*' => 'nullable|string|max:255',
    ], [
        'salvageValue.lt' => "Salvage value must be less than the Purchase cost",
        'purchaseDate.before_or_equal' => "The Purchase date must not have future dates",
    ]);

    // Determine the department ID
    $departmentId = $isAdmin ? $request->department : $userDept;

    // Additional Fields
    $customFields = $this->convertJSON($request->input('field.key'), $request->input('field.value'));

    // Generate Asset Code
    $department = DB::table('department')->where('id', $departmentId)->first();
    $departmentCode = $department->name;
    $lastID = department::where('name', $departmentCode)->max('assetSequence');
    $seq = $lastID ? $lastID + 1 : 1;
    $code = $departmentCode . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

    // Handle image upload
    $pathFile = null;
    if ($request->hasFile('asst_img')) {
        $image = $request->file('asst_img');
        $filename = $code . '-' . time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('asset_images', $filename, 'public');
        $pathFile = $path;
    }

    // Increment asset sequence in department
    department::where('id', $departmentId)->increment('assetSequence', 1);

    // Generate QR Code
    $qrCodePath = 'qrcodes/' . $code . '.png'; // Path to store the QR code
    $qrStoragePath = storage_path('app/public/' . $qrCodePath);

    // Ensure the directory exists
    if (!file_exists(storage_path('app/public/qrcodes'))) {
        mkdir(storage_path('app/public/qrcodes'), 0777, true);
    }

    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
        ->size(250)
        ->generate($code, $qrStoragePath);

    // Save asset details to the database
    DB::table('asset')->insert([
        'asst_img' => $pathFile,
        'name' => $request->assetname,
        'code' => $code,
        'purchase_cost' => $request->pCost,
        'purchase_date' => $request->purchasedDate,
        'depreciation' => $request->depreciation,
        'usage_lifespan' => $request->lifespan,
        'salvage_value' => $request->salvageValue,
        'ctg_ID' => $request->category,
        'custom_fields' => $customFields,
        'dept_ID' => $departmentId,
        'loc_key' => $request->loc,
        'model_key' => $request->mod,
        'manufacturer_key' => $request->mcft,
        'qr_img' => $qrCodePath, // Store the path to the QR code image file
        'created_at' => now(),
    ]);

    // Log the activity
    ActivityLog::create([
        'activity' => 'Create New Asset via System',
        'description' => "User {$deptHead->firstname} {$deptHead->lastname} created a new asset '{$request->assetname}' (Code: {$code}).",
        'userType' => $deptHead->usertype,
        'user_id' => $deptHead->id,
        'asset_id' => DB::getPdo()->lastInsertId(), // Get the last inserted asset ID
    ]);

    // Notify the admin about the new asset creation
    $notificationData = [
        'title' => 'New Asset Created',
        'message' => "A new asset '{$request->assetname}' (Code: {$code}) has been added.",
        'asset_name' => $request->assetname,
        'asset_code' => $code,
        'action_url' => route('asset'), // Adjust the route as needed
        'authorized_by' => $deptHead->id,
        'authorized_user_name' => "{$deptHead->firstname} {$deptHead->lastname}",
    ];

    // Send the notification to all admins
    $admins = User::where('usertype', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new \App\Notifications\SystemNotification($notificationData));
    }

    // Redirect based on user type
    $routePath = $isAdmin ? '/admin/assets' : '/asset';
    return redirect()->to($routePath)->with('success', 'New Asset Created');
}


    public static function assetCount()
    {
        // Dashboard
        $userDept = Auth::user()->dept_id;
        $usertype = Auth::user()->usertype;

        // Initialize the months array for the last 4 months (including the current month)
        $months = [];
        for ($i = 3; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i); // Get the date for each month
            $monthYear = $date->format('M Y'); // e.g., 'Jul 2024'
            $months[$monthYear] = ['active' => 0, 'under_maintenance' => 0]; // Initialize counts
        }

        // Query for counts by status (filtered by department if not admin)
        $statuses = ['active', 'deployed', 'under_maintenance', 'dispose'];
        foreach ($statuses as $status) {
            $query = DB::table('asset')->where('status', '=', $status);
            if ($usertype !== 'admin') {
                $query->where('dept_ID', '=', $userDept);
            }
            $asset[$status] = $query->count();
        }

        // Query for recently created assets (last 5) - filtered by department if not admin
        $newAssetCreatedQuery = assetModel::whereMonth('created_at', Carbon::now()->month)
            ->orderBy('created_at', 'desc')
            ->take(5);
        if ($usertype !== 'admin') {
            $newAssetCreatedQuery->where('dept_ID', $userDept);
        }
        $newAssetCreated = $newAssetCreatedQuery->get();

        // Query to fetch and group active assets by month (filtered by dept_ID if not admin)
        $dataActiveQuery = assetModel::where('status', 'active')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as monthYear'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('monthYear');
        if ($usertype !== 'admin') {
            $dataActiveQuery->where('dept_ID', $userDept);
        }
        $dataActive = $dataActiveQuery->get();

        // Query to fetch and group maintenance records by month for under maintenance assets
        $dataUnderMaintenanceQuery = Maintenance::join('asset', 'asset.id', '=', 'maintenance.asset_key')
            ->where('asset.status', 'under_maintenance')
            ->where('maintenance.status', 'approved')
            ->select(
                DB::raw('DATE_FORMAT(maintenance.created_at, "%b %Y") as monthYear'),
                DB::raw('COUNT(DISTINCT maintenance.id) as count') // Ensure distinct maintenance records are counted
            )
            ->groupBy('monthYear');
        if ($usertype !== 'admin') {
            $dataUnderMaintenanceQuery->where('asset.dept_ID', $userDept);
        }
        $dataUnderMaintenance = $dataUnderMaintenanceQuery->get();

        // Map the data into the months array (only for the last 4 months)
        foreach ($dataActive as $record) {
            if (isset($months[$record->monthYear])) {
                $months[$record->monthYear]['active'] = $record->count;
            }
        }

        foreach ($dataUnderMaintenance as $record) {
            if (isset($months[$record->monthYear])) {
                $months[$record->monthYear]['under_maintenance'] = $record->count;
            }
        }

        // Prepare data for the view
        $labels = array_keys($months); // Month labels (e.g., 'Jul 2024')
        $activeCounts = array_column($months, 'active'); // Active asset counts
        $maintenanceCounts = array_column($months, 'under_maintenance'); // Under maintenance counts

        // Return the view with the data
        $view = ($usertype === 'admin') ? 'admin.home' : 'dept_head.home';
        return view($view, [
            'asset' => $asset,
            'newAssetCreated' => $newAssetCreated,
            'labels' => $labels,
            'activeCounts' => $activeCounts,
            'maintenanceCounts' => $maintenanceCounts,
        ]);
    }


    public function update(Request $request, $id)
    {
        $user = Auth::user(); // Get the authenticated user
        $userType = $user->usertype; // Check user type (admin or dept_head)
        $userDept = $user->dept_id; // Get the user's department ID

        $validatedData = $request->validate([
            'asst_img' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'name' => 'sometimes|string',
            'category' => 'sometimes|exists:category,id',
            'usrAct' => 'nullable|exists:users,id',
            'mod' => 'sometimes|string',
            'mcft' => 'sometimes|exists:manufacturer,id',
            'loc' => 'sometimes|exists:location,id',
            'purchasedDate' => 'required|date|before_or_equal:today',
            'purchaseCost' => 'required|numeric|min:0.01',
            'lifespan' => 'required|integer|min:0',
            'salvageValue' => 'required|numeric|min:0|lt:purchaseCost',
            'depreciation' => 'required|numeric|min:0.01',
            'status' => 'sometimes|string|max:511',
            'field.key.*' => 'nullable|string|max:255',
            'field.value.*' => 'nullable|string|max:255',
            'current_image' => 'nullable|string', // Retain current image if not updated
        ],['salvageValue.lt' => "Salvage value must be less than the Purchased cost"]);
        // dd($request);

        // dd($validatedData);
        // Retrieve department info and generate a new asset code if needed
        $department = DB::table('department')->where('id', $userDept)->first();
        $departmentCode = $department->name;
        $lastID = department::where('name', $departmentCode)->max('assetSequence');
        $seq = $lastID ? $lastID + 1 : 1;
        $code = $departmentCode . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

        // Convert custom fields into JSON
        $fieldUpdate = $this->convertJSON(
            $request->input('field.key'),
            $request->input('field.value')
        );

        // Handle image upload or retain the current image
        $pathFile = $request->input('current_image'); // Default to current image path

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $code . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('asset_images', $filename, 'public');
            $pathFile = $path; // Use new image path
        }

        // Update asset data in the database
        $updatedRow = assetModel::findOrFail($id);
        $oldLastUser = $updatedRow->last_used_by;
        $updatedRow->update([
            'asst_img' => $pathFile,
            'name' => $validatedData["name"],
            'ctg_ID' => $validatedData["category"],
            'manufacturer_key' => $validatedData['mcft'],
            'model_key' => $validatedData["mod"],
            'loc_key' => $validatedData["loc"],
            'purchase_cost' => $validatedData["purchaseCost"],
            'purchase_date' => $validatedData["purchasedDate"],
            'depreciation' => $validatedData["depreciation"],
            'usage_lifespan' => $validatedData["lifespan"],
            'salvage_value' => $validatedData["salvageValue"],
            'last_used_by' => $validatedData["usrAct"],
            'status' => $validatedData["status"],
            'custom_fields' => $fieldUpdate,
            'updated_at' => now(),
        ]);

        // Log the asset update activity
        ActivityLog::create([
            'activity' => 'Edit/Update Asset Details',
            'description' => "Department Head {$user->firstname} {$user->lastname} updated asset '{$updatedRow->name}' (Code: {$updatedRow->code}).",
            'userType' => $user->usertype, // 'dept_head'
            'user_id' => $user->id,
            'asset_id' => $id,
        ]);

        $settingUsageLogs = new AsstController();
        $assetKey = assetModel::findOrFail($id);
        if(isset($validatedData['usrAct'])){
            $settingUsageLogs->assetAcquiredBy($validatedData["usrAct"],$assetKey->id );
        }
        if($oldLastUser !== $validatedData["usrAct"]){
            // dd("you are returning");
            $settingUsageLogs->assetReturnedBy($oldLastUser,$assetKey->id);
        }

        // Retrieve the updated asset to get the code
        $asset = DB::table('asset')->where('id', $id)->first();

        if ($updatedRow) {
            // Redirect based on user type
            $route = $userType === 'admin'
                ? 'adminAssetDetails'
                : 'assetDetails';

            return redirect()->route($route, ['id' => $asset->code])
                ->with('success', 'Asset updated successfully!');
        } else {
            $route = $userType === 'admin'
                ? 'adminAssetDetails'
                : 'assetDetails';

            return redirect()->route($route, ['id' => $asset->code])
                ->with('failed', 'Asset update failed!');
        }
    }

    public function searchFiltering(Request $request)
    {
        // Get search input (default to empty string if not provided)
        $search = $request->input('search', '');

        // Allowed statuses in predefined order
        $allowedStatuses = ['active', 'under_maintenance', 'deployed', 'disposed'];

        // Initialize query with necessary joins and filters
        $assetsQuery = assetModel::leftJoin('category', 'asset.ctg_ID', '=', 'category.id')
            ->where('asset.dept_ID', Auth::user()->dept_id)
            ->whereIn('asset.status', $allowedStatuses)
            ->select('asset.*', 'category.name as category_name');

        // Apply search filter if input is provided
        if (!empty($search)) {
            $assetsQuery->where(function ($query) use ($search) {
                $query->where('asset.name', 'LIKE', "%{$search}%")
                    ->orWhere('asset.code', 'LIKE', "%{$search}%")
                    ->orWhere('category.name', 'LIKE', "%{$search}%")
                    ->orWhere('asset.status', 'LIKE', "%{$search}%");
            });
        }

        // Sort by category name (alphabetically) and then by status in custom order
        $assetsQuery->orderByRaw("
                                CASE
                                    WHEN asset.status = 'active' THEN 0
                                    WHEN asset.status = 'under_maintenance' THEN 1
                                    WHEN asset.status = 'deployed' THEN 2
                                    WHEN asset.status = 'disposed' THEN 3
                                    ELSE 4
                                END
                            ")
            ->orderBy('code', 'asc')
            ->orderBy('asset.created_at', 'desc');

        // Paginate results
        $assets = $assetsQuery->paginate(10)->appends($request->all());
        $categoriesList = DB::table('category')->where('dept_ID', Auth::user()->dept_ID)->get();

        // Return the view with filtered and sorted results
        return view('dept_head.asset', compact('assets','categoriesList'));
    }

    public function delete($id)
    {
        $assetDel = assetModel::findOrFail($id); // Find asset by ID

        $userLogged = Auth::user();

        $assetDel->updated_at = now(); // Optionally update the timestamp

        $assetDel->delete();

        // Delete the asset

        // Log the activity
        ActivityLog::create([
            'activity' => 'Asset is Deleted via System',
            'description' => "User {$userLogged->firstname} {$userLogged->lastname} Delete a asset '{$userLogged->assetname}' (Code: {$assetDel->code}).",
            'userType' => $userLogged->usertype,
            'user_id' => $userLogged->id,
            'asset_id' => $assetDel->id, // Get the last inserted asset ID
        ]);

        // Notify the admin about the new asset creation
        $notificationData = [
            'title' => 'Asset Deleted',
            'message' => "Asset '{$assetDel->assetname}' (Code: {$assetDel->code}) has been deleted.",
            'asset_name' => $assetDel->name,
            'asset_code' => $assetDel->code,
            'authorized_by' => $userLogged->id,
            'authorized_user_name' => "{$userLogged->firstname} {$userLogged->lastname}",
        ];

        // Send the notification to all admins
        $admins = User::where('usertype', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SystemNotification($notificationData));
        }

        $routPath = Auth::user()->usertype === 'admin' ? 'assetList' : 'asset';

        return redirect()->route($routPath)->with('success', 'Asset Deleted Successfully');
    }

    public function UsageHistory($id)
    {
        return AssignedToUser::with(['assetUserBy', 'assignedBy'])
            ->where('asset_id', $id)->get();
    }

    public function showDetails($code)
    {
        // Get the logged-in user's department ID and user type
        $userDept = Auth::user()->dept_id;
        $userType = Auth::user()->usertype;

        // Retrieve necessary data from related tables
        $department = ['list' => DB::table('department')->get()];
        $categories = [
            'ctglist' => DB::table('category')->when($userType != 'admin', function ($query) use ($userDept) {
                return $query->where('dept_ID', $userDept);
            })->get()
        ];
        $location = ['locs' => DB::table('location')->get()];
        $model = ['mod' => DB::table('model')->get()];
        $manufacturer = ['mcft' => DB::table('manufacturer')->get()];
        $status = ['sts' => ['active', 'deployed', 'need repair', 'under_maintenance', 'dispose']];

        // Build the query to retrieve the asset data based on the asset code
        $retrieveDataQuery = assetModel::where('code', $code)
            ->leftJoin('department', 'dept_ID', '=', 'department.id')
            ->leftJoin('category', 'ctg_ID', '=', 'category.id')
            ->leftJoin('model', 'model_key', '=', 'model.id')
            ->leftJoin('manufacturer', 'manufacturer_key', '=', 'manufacturer.id')
            ->leftJoin('location', 'loc_key', '=', 'location.id')
            ->leftJoin('users', 'users.id', '=', 'asset.last_used_by')
            ->select(
                'asset.id',
                'asset.asst_img',
                'asset.name',
                'asset.code',
                'asset.depreciation',
                'asset.purchase_cost',
                'asset.purchase_date',
                'asset.usage_lifespan',
                'asset.salvage_value',
                'asset.status',
                'asset.last_used_by',
                'asset.custom_fields',
                'asset.qr_img',
                'asset.dept_ID',
                'asset.created_at',
                'asset.updated_at',
                'users.firstname',
                'users.lastname',
                'users.middlename',
                'category.name as category',
                'model.name as model',
                'location.name as location',
                'manufacturer.name as manufacturer'
            );
        // Apply department filter for dept_head and user
        if ($userType != 'admin') {
            $retrieveDataQuery->where('asset.dept_ID', '=', $userDept);
        }

        // Retrieve the asset data
        $retrieveData = $retrieveDataQuery->first();




        // If no asset is found, redirect with an error message
        if (!$retrieveData) {
            return redirect()->route('asset')->with('error', 'Asset not found.');
        }

        $usersDeptId = ($userType === 'admin') ? $retrieveData->dept_ID : $userDept;

        // dd($userType,$retrieveData->dept_ID ,$retrieveData);
        $allUserInDept = User::where('dept_id', $usersDeptId)
            ->select(
                'Users.id',
                'Users.firstname',
                'Users.lastname',
            )
            ->get();
        // Retrieve asset and department data
        $asset = assetModel::find($retrieveData->id);
        $department = Department::find($asset->dept_ID);

            // Decode custom_fields from both asset and department (assuming they are stored as JSON)
            $assetCustomFields = json_decode($asset->custom_fields, true) ?? [];
            $departmentCustomFields = json_decode($department->custom_fields, true) ?? [];



            // Create an empty array to hold the updated custom fields
            $updatedCustomFields = [];
            // dd($departmentCustomFields ,$assetCustomFields);
            // dd($departmentCustomFields);
            // Loop through the department custom fields and map the values from the asset custom fields
            foreach ($departmentCustomFields as $deptField) {
                $fieldName = $deptField['name']; // For example: "RAM"

            // Check if the asset has a value for this field
            $fieldValue = isset($assetCustomFields[$fieldName]) ? $assetCustomFields[$fieldName] : null;


            // Add the field to the updated custom fields array
            $updatedCustomFields[] = [
                'name' => $fieldName,
                'value' => $fieldValue, // Take the value from the asset
                'type' => $deptField['type'], // Keep type from department
                'helper' => $deptField['helptext'] // Keep helper from department
            ];
        }

        // Retrieve related maintenance data for the asset
        $assetRet = Maintenance::where('asset_key', $retrieveData->id) // Use asset ID to match
            ->where('is_completed', 1)
            ->leftjoin('users', 'users.id', '=', 'maintenance.requestor')
            ->leftjoin('users as authorized', 'authorized.id', '=', 'maintenance.authorized_by')
            ->select(
                'users.firstname as fname',
                'users.lastname as lname',
                'maintenance.type',
                'authorized.firstname as authorized_fname',
                'authorized.lastname as authorized_lname',
                'maintenance.created_at',
                'maintenance.cost',
                'maintenance.reason',
                'maintenance.completion_date as complete'
            )
            ->get();

        // fetching
        $usageLogsAsset = $this->UsageHistory($retrieveData->id);

        // Determine the view based on user type
        $view = ($userType == 'admin') ? 'admin.assetDetail' : 'dept_head.assetDetail';
        // Return the appropriate view with the asset data, including the QR code
        return view($view, compact(
            'retrieveData',
            'updatedCustomFields',
            'department',
            'categories',
            'location',
            'model',
            'status',
            'manufacturer',
            'assetRet',
            'allUserInDept',
            'usageLogsAsset'
        ));
    }

    public function downloadCsvTemplate()
    {
        // Define the column names matching the asset table structure
        $columns = [
            'name',              // Asset name
            'purchase_date',     // Purchase date (YYYY-MM-DD format)
            'purchase_cost',     // Purchase cost
            'depreciation',      // Depreciation value
            'salvage_value',     // Salvage value after depreciation
            'usage_lifespan',    // Usage lifespan in years (nullable)
            'category',          // Asset category name
            'manufacturer',      // Manufacturer name
            'model',             // Model name
            'location',          // Location name
            'status'             // Asset status (active, deployed, under_maintenance, disposed)
        ];

        // Add a sample row with data matching the asset schema
        $sampleData = [
            'Sample Asset',             // Asset name
            now()->format('Y-m-d'),      // Purchase date (today's date)
            '10000',                    // Purchase cost
            '500',                      // Depreciation value
            '1000',                     // Salvage value
            '10',                       // Usage lifespan (in years)
            'IT Equipment',             // Example category
            'Sony',                     // Example manufacturer
            'Model X',                  // Example model
            'HQ',                       // Example location
            'active'                    // Asset status
        ];

        // Convert the column names and sample data into CSV format
        $csvContent = implode(",", $columns) . "\n";
        $csvContent .= implode(",", $sampleData) . "\n"; // Add sample row

        // Return the CSV as a download
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="asset_template.csv"');
    }

    public function uploadCsv(Request $request)
    {
        try {
            Log::info('Starting CSV upload process.');

            // Validate headers and rows
            $validated = $request->validate([
                'headers' => 'required|array',
                'rows' => 'required|array',
            ]);

            $headers = $request->input('headers');
            $rows = $request->input('rows');

            if (!$rows || count($rows) == 0) {
                Log::warning('No rows provided in the CSV.');
                return response()->json(['success' => false, 'message' => 'No rows provided.'], 400);
            }

            $userDept = Auth::user()->dept_id;
            $deptHead = Auth::user(); // The department head who uploaded the CSV
            $department = DB::table('department')->where('id', $userDept)->first();
            Log::info('Authenticated user department ID: ' . $userDept);

            // Initialize the $assets array to collect asset names and codes
            $assets = [];

            foreach ($rows as $row) {
                if (count($row) < count($headers)) {
                    Log::warning('Skipping a row due to insufficient columns.', ['row' => $row]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient columns in a row. Check the CSV format.',
                        'row' => $row
                    ], 400);
                }

                $rowData = array_combine($headers, $row);

                // Validate and convert date
                try {
                    $purchaseDate = null;
                    $formats = ['d/m/Y', 'Y-m-d', 'm-d-Y', 'd-M-Y']; // Add more if needed

                    foreach ($formats as $format) {
                        try {
                            $purchaseDate = Carbon::createFromFormat($format, $rowData['purchase_date']);
                            break; // Stop if a valid date is found
                        } catch (\Exception $e) {
                            continue; // Try the next format
                        }
                    }

                    if ($purchaseDate) {
                        $purchaseDate = $purchaseDate->format('Y-m-d'); // Convert to MySQL format
                    } else {
                        throw new \Exception('Invalid date format.');
                    }
                } catch (\Exception $e) {
                    Log::error('Invalid date format in row.', [
                        'row' => $rowData,
                        'error' => $e->getMessage()
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid date format in row.',
                        'row' => $rowData
                    ], 400);
                }

                try {
                    // Create or retrieve related models
                    $category = category::firstOrCreate(
                        ['name' => $rowData['category'], 'dept_ID' => $userDept],
                        ['description' => 'new item description']
                    );

                    $location = locationModel::firstOrCreate(
                        ['name' => $rowData['location'], 'dept_ID' => $userDept],
                        ['description' => 'new item description']
                    );

                    $manufacturer = Manufacturer::firstOrCreate(
                        ['name' => $rowData['manufacturer'], 'dept_ID' => $userDept],
                        ['description' => 'new item description']
                    );

                    $model = ModelAsset::firstOrCreate(
                        ['name' => $rowData['model'], 'dept_ID' => $userDept],
                        ['description' => 'new item description']
                    );

                    // Generate asset code based on department sequence
                    $department = DB::table('department')->where('id', $userDept)->first();
                    $departmentCode = $department->name ?? 'UNKNOWN';
                    $lastID = department::where('name', $departmentCode)->max('assetSequence');
                    $seq = $lastID ? $lastID + 1 : 1;
                    $assetCode = $departmentCode . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
                    department::where('id', $userDept)->increment('assetSequence', 1);

                    Log::info('Generated asset code: ' . $assetCode);

                    // Define QR code path and ensure directory exists
                    $qrCodePath = 'qrcodes/' . $assetCode . '.png';
                    $qrStoragePath = storage_path('app/public/' . $qrCodePath);

                    if (!file_exists(storage_path('app/public/qrcodes'))) {
                        mkdir(storage_path('app/public/qrcodes'), 0777, true);
                    }

                    // Generate QR code
                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                        ->size(250)
                        ->generate($assetCode, $qrStoragePath);
                    Log::info('QR code generated and saved.', ['path' => $qrCodePath]);

                    // Create the asset
                    assetModel::create([
                        'code' => $assetCode,
                        'name' => $rowData['name'],
                        'salvage_value' => (int) $rowData['salvage_value'],
                        'depreciation' => (int) $rowData['depreciation'],
                        'purchase_date' => $purchaseDate,
                        'purchase_cost' => (int) $rowData['purchase_cost'],
                        'usage_lifespan' => !empty($rowData['usage_lifespan']) ? (int) $rowData['usage_lifespan'] : null,
                        'ctg_ID' => $category->id,
                        'manufacturer_key' => $manufacturer->id,
                        'model_key' => $model->id,
                        'loc_key' => $location->id,
                        'dept_ID' => $userDept,
                        'status' => $rowData['status'] ?? 'active',
                        'qr_img' => $qrCodePath,
                    ]);
                    // Add asset name and code to the $assets array
                    $assets[] = ['name' => $rowData['name'], 'code' => $assetCode];
                    Log::info('Asset created successfully.', ['code' => $assetCode]);
                } catch (\Exception $e) {
                    Log::error('Error inserting asset: ' . $e->getMessage(), ['row' => $rowData]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error inserting asset.',
                        'row' => $rowData,
                        'error' => $e->getMessage()
                    ], 400);
                }
            }

            // Activity Log: Log the import action
            ActivityLog::create([
                'activity' => 'Add New Asset via Import',
                'description' => "Department Head {$deptHead->firstname} {$deptHead->lastname} imported new assets via CSV into the {$department->name} department.",
                'userType' => $deptHead->usertype, // 'dept_head'
                'user_id' => $deptHead->id,
            ]);

            $notificationData = [
                'title' => 'New Assets Added via CSV Import',
                'message' => "New assets were added in '{$department->name}' Department.",
                'asset_name' => 'Multiple Assets',
                'asset_code' => 'System Generated Code',
                'authorized_by' => $deptHead->id,
                'authorized_user_name' => "{$deptHead->firstname} {$deptHead->lastname}",
                'action_url' => route('asset'),
            ];

            $admins = User::where('usertype', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\SystemNotification($notificationData));
            }

            Log::info('CSV uploaded successfully.');
            return response()->json([
                'success' => true,
                'message' => 'CSV uploaded successfully'
            ]);
        } catch (\Throwable $th) {
            Log::error('Error during CSV upload: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading CSV. Check logs.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
