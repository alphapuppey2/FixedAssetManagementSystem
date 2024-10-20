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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\HttpFoundation\StreamedResponse;

class AsstController extends Controller
{
    public function showAllAssets()
    {
        $assets = DB::table('asset')
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->select('asset.id', 'asset.code', 'asset.name', 'asset.asst_img', 'asset.purchase_cost', 'asset.salvage_value', 'asset.depreciation', 'asset.usage_lifespan', 'asset.status', 'category.name as category', 'department.name as department')
            ->orderByRaw("
            CASE
            WHEN asset.status = 'active' THEN 0
            WHEN asset.status = 'under_maintenance' THEN 1
            WHEN asset.status = 'deployed' THEN 2
            WHEN asset.status = 'disposed' THEN 3
            ELSE 4
            END
            ")
            ->orderBy(DB::raw("
            IF(asset.name REGEXP '[0-9]+$',
                CAST(REGEXP_SUBSTR(asset.name, '[0-9]+$') AS UNSIGNED),
                asset.id
            )
        "), 'asc')
            ->paginate(10);

        return view("admin.assetList", compact('assets'));
    }

    public function showAssetsByDept($dept = null)
    {
        $query = DB::table('asset')
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->select('asset.id', 'asset.code', 'asset.name', 'asset.asst_img', 'asset.purchase_cost', 'asset.salvage_value', 'asset.depreciation', 'asset.usage_lifespan', 'asset.status', 'category.name as category', 'department.name as department')
            ->orderBy('asset.code', 'asc');

        // If department is selected, filter by department ID
        if ($dept) {
            $query->where('asset.dept_ID', $dept);
        }

        $query
        ->orderBy('asset.name', 'asc')
        ->orderByRaw("
            CASE
                WHEN asset.status = 'active' THEN 0
                WHEN asset.status = 'under_maintenance' THEN 1
                WHEN asset.status = 'deployed' THEN 2
                WHEN asset.status = 'disposed' THEN 3
                ELSE 4
            END
        ")->orderBy(DB::raw("
        IF(asset.name REGEXP '[0-9]+$',
            CAST(REGEXP_SUBSTR(asset.name, '[0-9]+$') AS UNSIGNED),
            asset.id
        )
    "), 'asc')
            ;


        $assets = $query->paginate(10);


        return view("admin.assetList", compact('assets'));
    }

    public function searchAssets(Request $request)
    {
        // Get search query and rows per page
        $query = $request->input('query');
        $perPage = $request->input('perPage', 10); // Default to 10 rows per page
        $deptId = $request->input('dept'); // Get the department ID from the request, if present

        // Build the query to search assets by name or code
        $assets = DB::table('asset')
            ->when($deptId, function ($query, $deptId) {
                // Apply department filter if deptId is provided
                return $query->where('asset.dept_ID', '=', $deptId);
            })
            ->where(function ($subquery) use ($query) {
                // Search by asset name or code
                $subquery->where('asset.name', 'like', '%' . $query . '%')
                    ->orWhere('asset.code', 'like', '%' . $query . '%');
            })
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->select('asset.*', 'department.name as department', 'category.name as category')
            ->orderByRaw("
            CASE
            WHEN asset.status = 'active' THEN 0
            WHEN asset.status = 'under_maintenance' THEN 1
            WHEN asset.status = 'deployed' THEN 2
            WHEN asset.status = 'disposed' THEN 3
            ELSE 4
            END
            ")
            ->orderBy('department', 'asc')
            ->orderBy(DB::raw("
            IF(asset.name REGEXP '[0-9]+$',
                CAST(REGEXP_SUBSTR(asset.name, '[0-9]+$') AS UNSIGNED),
                asset.id
            )
        "), 'asc')// Order by name or another column if needed
            ->paginate($perPage);

        // Return the view with the filtered assets
        return view('admin.assetList', compact('assets'));
    }


    //KANI
public function showDeptAsset(Request $request)
{
    $userDept = Auth::user()->dept_id;

    // Get search query, sort field, and direction
    $search = $request->input('search');
    $sortField = $request->input('sort', 'code'); // Default sort field
    $sortDirection = $request->input('direction', 'asc'); // Default direction

    // Filter parameters
    $status = $request->input('status');
    $category = $request->input('category');

    // Allow only these fields to be sorted
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
            return $query->where('asset.name', 'like', "%{$search}%");
        })
        ->when($status, function ($query, $status) {
            return $query->where('asset.status', $status);
        })
        ->when($category, function ($query, $category) {
            return $query->where('category.name', 'like', "%{$category}%");
        })
        ->select(
            'asset.id',
            'asset.code',
            'asset.name',
            'asset.status',
            'category.name as category_name',
            'department.name as department'
        )
        // ->orderByRaw("
        //     CASE
        //     WHEN asset.status = 'active' THEN 0
        //     WHEN asset.status = 'under_maintenance' THEN 1
        //     WHEN asset.status = 'deployed' THEN 2
        //     WHEN asset.status = 'disposed' THEN 3
        //     ELSE 4
        //     END
        //     ") //hello
        ->orderBy($sortField, $sortDirection)
        ->paginate(10);

    // Return the view with assets and query parameters
    return view('dept_head.asset', compact('assets'));
}




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
        $usrDPT = Auth::user()->dept_id;
        $department = department::find($usrDPT);

        $categories = array('ctglist' => DB::table('category')->where('dept_ID', $usrDPT)->get());
        $location = array('locs' => DB::table('location')->where('dept_ID', $usrDPT)->get());
        $model = array('mod' => DB::table('model')->where('dept_ID', $usrDPT)->get());
        $manufacturer = array('mcft' => DB::table('manufacturer')->where('dept_ID', $usrDPT)->get());
        $addInfos = json_decode($department->custom_fields);


        if ($categories['ctglist']->isEmpty() || $location['locs']->isEmpty() || $model['mod']->isEmpty() || $manufacturer['mcft']->isEmpty()) {
            return redirect()->back()->with('noSettings', 'Your setting is null. Please set up your settings.');
        }

        return view('dept_head.createAsset', compact('addInfos', 'categories', 'location', 'model', 'manufacturer'));
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
        // Validate the request
        $request->validate([
            'asst_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif',
            'assetname' => 'required',
            'category' => 'required',
            'purchased' => 'required|date',
            'loc' => 'required',
            'mod' => 'required',
            'mcft' => 'required',
            'field.key.*' => 'nullable|string|max:255',
            'field.value.*' => 'nullable|string|max:255',
        ]);

        // Additional Fields
        $customFields = $this->convertJSON($request->input('field.key'), $request->input('field.value'));

        // Generate Asset Code
        $department = DB::table('department')->where('id', $userDept)->first();
        $departmentCode = $department->name;
        $lastID = department::where('name', $departmentCode)->max('assetSequence');
        $seq = $lastID ? $lastID + 1 : 1;
        $code = $departmentCode . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

        // Handle image upload
        $pathFile = NULL;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $code . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('asset_images', $filename, 'public');
            $pathFile = $path;
        }

        // Increment asset sequence in department
        department::where('id', $userDept)->increment('assetSequence', 1);

        // Calculate depreciation (Straight Line method)

        // ** Generate QR Code based on Asset Code using Simple QR and Imagick **
        $qrCodePath = 'qrcodes/' . $code . '.png';  // Path to store the QR code
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
            'ctg_ID' => $request->category,
            'custom_fields' => $customFields,
            'dept_ID' => $userDept,
            'loc_key' => $request->loc,
            'model_key' => $request->mod,
            'manufacturer_key' => $request->mcft,
            'qr_img' => $qrCodePath,  // Store the path to the QR code image file
            'created_at' => now(),
        ]);

        // Notify the admin about the new asset creation
        $notificationData = [
            'title' => 'New Asset Created',
            'message' => "A new asset '{$request->assetname}' (Code: {$code}) has been added via system input.",
            'asset_name' => $request->assetname,
            'asset_code' => $code,
            'action_url' => route('asset'), // Adjust the route as needed
            'authorized_by' => $deptHead->id,
            'authorized_user_name' => "{$deptHead->firstname} {$deptHead->lastname}",
        ];

        // Send the notification to all admins
        $admins = \App\Models\User::where('usertype', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\SystemNotification($notificationData));
        }

        return redirect()->to('/asset')->with('success', 'New Asset Created');
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

    if($usertype !== 'admin'){
        $asset['active'] = DB::table('asset')
        ->where('status', '=', 'active')
        ->where('dept_ID', '=', $userDept)
        ->count();

    $asset['deploy'] = DB::table('asset')
        ->where('status', '=', 'deployed')
        ->where('dept_ID', '=', $userDept)
        ->count();

    $asset['under_maintenance'] = DB::table('asset')
        ->where('status', '=', 'under_maintenance')
        ->where('dept_ID', '=', $userDept)
        ->count();

    $asset['dispose'] = DB::table('asset')
        ->where('status', '=', 'dispose')
        ->where('dept_ID', '=', $userDept)
        ->count();

     // Query to fetch and group active assets by month
     $dataActive = assetModel::where('status', 'active')
     ->where('dept_ID', Auth::user()->dept_id)
     ->select(
         DB::raw('DATE_FORMAT(IFNULL(updated_at, created_at), "%b %Y") as monthYear'),
         DB::raw('COUNT(*) as count')
     )
     ->groupBy('monthYear')
     ->get();

 // Query to fetch and group under maintenance assets by month
 $dataUnderMaintenance = assetModel::where('status', 'under_maintenance')
 ->where('dept_ID', Auth::user()->dept_id)
 ->select(
         DB::raw('DATE_FORMAT(IFNULL(updated_at, created_at), "%b %Y") as monthYear'),
         DB::raw('COUNT(*) as count')
     )
     ->groupBy('monthYear')
     ->get();
     $newAssetCreated = assetModel::where('dept_ID', $userDept)
         ->whereMonth('created_at', Carbon::now()->month)
         ->orderBy('created_at', 'desc')
         ->take(5)
         ->get();

    }
    else{

        //admin Dashboard
        $asset['active'] = DB::table('asset')
        ->where('status', '=', 'active')
        ->count();

    $asset['deploy'] = DB::table('asset')
        ->where('status', '=', 'deployed')
        ->count();

    $asset['under_maintenance'] = DB::table('asset')
        ->where('status', '=', 'under_maintenance')
        ->count();

    $asset['dispose'] = DB::table('asset')
        ->where('status', '=', 'dispose')
        ->count();

        $newAssetCreated = assetModel::whereMonth('created_at', Carbon::now()->month)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
    }




    // Query to fetch and group active assets by month
    $dataActive = assetModel::where('status', 'active')
        ->where('dept_ID', Auth::user()->dept_id)
        ->select(
            DB::raw('DATE_FORMAT(IFNULL(updated_at, created_at), "%b %Y") as monthYear'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('monthYear')
        ->get();

    // Query to fetch and group under maintenance assets by month
    $dataUnderMaintenance = assetModel::where('status', 'under_maintenance')
    ->where('dept_ID', Auth::user()->dept_id)
        ->select(
            DB::raw('DATE_FORMAT(IFNULL(updated_at, created_at), "%b %Y") as monthYear'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('monthYear')
        ->get();

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
   if($usertype === 'admin'){
    return view('admin.home', [
        'asset' => $asset,
        'newAssetCreated' => $newAssetCreated,
        'labels' => $labels,
        'activeCounts' => $activeCounts,
        'maintenanceCounts' => $maintenanceCounts,
    ]);
   }else{
    return view('dept_head.home', [
        'asset' => $asset,
        'newAssetCreated' => $newAssetCreated,
        'labels' => $labels,
        'activeCounts' => $activeCounts,
        'maintenanceCounts' => $maintenanceCounts,
    ]);

   }
}


    public function update(Request $request, $id)
    {
        $user = Auth::user(); // Get the authenticated user
        $userType = $user->usertype; // Check user type (admin or dept_head)
        $userDept = $user->dept_id; // Get the user's department ID

        $validatedData = $request->validate([
            'asst_img' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'name' => 'required|string',
            'category' => 'required|exists:category,id',
            'usrAct' => 'nullable|exists:users,id',
            'mod' => 'required|string',
            'mcft' => 'required|exists:manufacturer,id',
            'loc' => 'required|exists:location,id',
            'status' => 'nullable|string|max:511',
            'field.key.*' => 'nullable|string|max:255',
            'field.value.*' => 'nullable|string|max:255',
            'current_image' => 'nullable|string', // Retain current image if not updated
        ]);
        // dd($request);


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
            'last_used_by' => $validatedData["usrAct"],
            'status' => $validatedData["status"],
            'custom_fields' => $fieldUpdate,
            'updated_at' => now(),
        ]);

        $settingUsageLogs = new AsstController();
        $assetKey = assetModel::findOrFail($id);
        if(isset($validatedData['usrAct'])){

            $settingUsageLogs->assetAcquiredBy($validatedData["usrAct"],$assetKey->id );
        }
        if($oldLastUser !== $validatedData["usrAct"]){
            $settingUsageLogs->assetReturnedBy($validatedData["usrAct"],$assetKey->id);
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


    // app/Http/Controllers/AssetController.php

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

    // Return the view with filtered and sorted results
    return view('dept_head.asset', compact('assets'));
}




    public function delete($id)
    {
        $assetDel = assetModel::findOrFail($id); // Find asset by ID

        $assetDel->updated_at = now(); // Optionally update the timestamp

        $assetDel->delete(); // Delete the asset

        return redirect()->route('asset')->with('success', 'Asset Deleted Successfully');
    }

    public function UsageHistory($id){
        return AssignedToUser::with(['assetUserBy','assignedBy'])
                                ->where('asset_id',$id)->get();
    }
    //If modify make sure to update show details in QRUserCotroller.php
    //Both same functionalities but different parameters
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
        $allUserInDept = User::where('dept_id' , $userDept)
                                    ->select(
                                        'Users.id',
                                        'Users.firstname',
                                        'Users.lastname',
                                    )
                                    ->get();

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
                'asset.status',
                'asset.last_used_by',
                'asset.custom_fields',
                'asset.qr_img',
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

            // dd($retrieveData);
            // If no asset is found, redirect with an error message
            if (!$retrieveData) {
                return redirect()->route('asset')->with('error', 'Asset not found.');
            }
            // Retrieve asset and department data
            $asset = assetModel::find($retrieveData->id);
            $department = Department::find($asset->dept_ID);

            // Decode custom_fields from both asset and department (assuming they are stored as JSON)
            $assetCustomFields = json_decode($asset->custom_fields, true) ?? [];
            $departmentCustomFields = json_decode($department->custom_fields, true) ?? [];

            // Create an empty array to hold the updated custom fields
            $updatedCustomFields = [];
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
                ->get()
                ;

                // fetching
                $usageLogsAsset = $this->UsageHistory($retrieveData->id);


                // dd($usageLogsAsset);
            // dd($allUserInDept);

        // Determine the view based on user type
        $view = ($userType == 'admin') ? 'admin.assetDetail' : 'dept_head.assetDetail';
        // Return the appropriate view with the asset data, including the QR code
        return view($view, compact('retrieveData',
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

            Log::info('CSV data validated successfully.');

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
                Log::info('Processing row data.', $rowData);

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
                    Log::info('Category created or retrieved.', ['category_id' => $category->id]);

                    $location = locationModel::firstOrCreate(
                        ['name' => $rowData['location'], 'dept_ID' => $userDept],
                        ['description' => 'new item description']
                    );
                    Log::info('Location created or retrieved.', ['location_id' => $location->id]);

                    $manufacturer = Manufacturer::firstOrCreate(
                        ['name' => $rowData['manufacturer'], 'dept_ID' => $userDept],
                        ['description' => 'new item description']
                    );
                    Log::info('Manufacturer created or retrieved.', ['manufacturer_id' => $manufacturer->id]);

                    $model = ModelAsset::firstOrCreate(
                        ['name' => $rowData['model'], 'dept_ID' => $userDept],
                        ['description' => 'new item description']
                    );
                    Log::info('Model created or retrieved.', ['model_id' => $model->id]);

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

            // Use the first asset's details for notification
            $firstAsset = $assets[0] ?? ['name' => 'Unknown', 'code' => 'Unknown'];

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
