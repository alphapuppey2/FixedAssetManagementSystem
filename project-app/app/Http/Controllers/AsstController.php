<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\department;
use App\Models\Maintenance;
use App\Models\assetModel;

use App\Models\category;
use App\Models\locationModel;
use App\Models\Manufacturer;
use App\Models\ModelAsset;

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
            ->select('asset.id', 'asset.code', 'asset.name', 'asset.asst_img', 'asset.cost', 'asset.salvage_value', 'asset.depreciation', 'asset.usage_lifespan', 'asset.status', 'category.name as category', 'department.name as department')
            ->orderBy('asset.code', 'asc')
            ->paginate(10);

        return view("admin.assetList", compact('assets'));
    }

    public function showAssetsByDept($dept = null)
    {
        $query = DB::table('asset')
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->select('asset.id', 'asset.code', 'asset.name', 'asset.asst_img', 'asset.cost', 'asset.salvage_value', 'asset.depreciation', 'asset.usage_lifespan', 'asset.status', 'category.name as category', 'department.name as department')
            ->orderBy('asset.code', 'asc');

        // If department is selected, filter by department ID
        if ($dept) {
            $query->where('asset.dept_ID', $dept);
        }

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
            ->orderBy('asset.name', 'asc') // Order by name or another column if needed
            ->paginate($perPage);

        // Return the view with the filtered assets
        return view('admin.assetList', compact('assets'));
    }

    public function showDeptAsset()
    {
        $userDept = Auth::user()->dept_id;

        $asset = DB::table('asset')
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->where('asset.dept_ID', $userDept)
            ->where('asset.isDeleted', 0)
            ->select(
                'asset.id',
                'asset.code',
                'asset.name',
                'asset.asst_img',
                'asset.status',
                'category.name as category',
                'department.name as department'
            )
            ->orderByRaw("
            CASE
                WHEN asset.status = 'active' THEN 0
                WHEN asset.status = 'under Maintenance' THEN 1
                WHEN asset.status = 'deployed' THEN 2
                WHEN asset.status = 'disposed' THEN 3
                ELSE 4
            END
        ")
            ->orderBy('code', 'asc')
            ->orderBy('asset.created_at', 'desc') // Then sort by created_at
            ->paginate(10);

        return view("dept_head.asset", compact('asset'));
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
            return redirect()->back()->with('toast', 'Your setting is null. Please set up your settings.');
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

        return redirect()->to('/asset')->with('success', 'New Asset Created');
    }

    public static function assetCount()
{
    // Dashboard
    $userDept = Auth::user()->dept_id;

    $asset['active'] = DB::table('asset')
        ->where('status', '=', 'active')
        ->where('dept_ID', '=', $userDept)
        ->whereRaw('MONTH(IFNULL(updated_at, created_at)) = ?', [Carbon::now()->month])
        ->count();

    $asset['under_maintenance'] = DB::table('asset')
        ->where('status', '=', 'under_maintenance')
        ->where('dept_ID', '=', $userDept)
        ->whereRaw('MONTH(IFNULL(updated_at, created_at)) = ?', [Carbon::now()->month])
        ->count();

    $asset['dispose'] = DB::table('asset')
        ->where('status', '=', 'dispose')
        ->where('dept_ID', '=', $userDept)
        ->count();

    $asset['deploy'] = DB::table('asset')
        ->where('status', '=', 'deployed')
        ->where('dept_ID', '=', $userDept)
        ->count();

    $newAssetCreated = assetModel::where('dept_ID', $userDept)
        ->whereMonth('created_at', Carbon::now()->month)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    // Initialize arrays for month data
    $monthsActive = [];
    $monthsUnderMaintenance = [];
    for ($i = 4; $i >= 0; $i--) {
        $date = Carbon::now()->subMonths($i);
        $monthYear = $date->format('M Y');
        $monthsActive[$monthYear] = 0;
        $monthsUnderMaintenance[$monthYear] = 0;
    }

    // Retrieve data for Active assets grouped by month and year
    $dataActive = assetModel::where('dept_ID', $userDept)
        ->whereBetween(DB::raw('DATE_FORMAT(IFNULL(updated_at, created_at), "%Y-%m")'), [
            Carbon::now()->subMonths(4)->format('Y-m'),
            Carbon::now()->format('Y-m')
        ])
        ->where('status', 'active')
        ->select(
            DB::raw('DATE_FORMAT(IFNULL(updated_at, created_at), "%b %Y") as monthYear'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('monthYear')
        ->get();

    // Retrieve data for Under Maintenance assets grouped by month and year
    $dataUnderMaintenance = assetModel::where('dept_ID', $userDept)
        ->whereBetween(DB::raw('DATE_FORMAT(IFNULL(updated_at, created_at), "%Y-%m")'), [
            Carbon::now()->subMonths(4)->format('Y-m'),
            Carbon::now()->format('Y-m')
        ])
        ->where('status', 'under_maintenance')
        ->select(
            DB::raw('DATE_FORMAT(IFNULL(updated_at, created_at), "%b %Y") as monthYear'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('monthYear')
        ->get();

    // Map the retrieved data to the month arrays
    foreach ($dataActive as $record) {
        $monthsActive[$record->monthYear] = $record->count;
    }
    foreach ($dataUnderMaintenance as $record) {
        $monthsUnderMaintenance[$record->monthYear] = $record->count;
    }

    // Return the view with the data
    return view('dept_head.home', [
        'asset' => $asset,
        'newAssetCreated' => $newAssetCreated,
        'Amonths' => array_keys($monthsActive),
        'Acounts' => array_values($monthsActive),
        'UMmonths' => array_keys($monthsUnderMaintenance),
        'UMcounts' => array_values($monthsUnderMaintenance),
    ]);
}

    public function update(Request $request, $id)
    {
        $userDept = Auth::user()->dept_id;

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
            'current_image' => 'nullable|string', // For retaining current image
        ]);

        // Retrieve department information for generating the asset code
        $department = DB::table('department')->where('id', $userDept)->first();
        $departmentCode = $department->name;
        $lastID = department::where('name', $departmentCode)->max('assetSequence');
        $seq = $lastID ? $lastID + 1 : 1;
        $code = $departmentCode . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

        $fieldUpdate = $this->convertJSON($request->input('field.key'), $request->input('field.value'));

        // Handle image upload or retain the current image
        $pathFile = $request->input('current_image'); // Use current image path by default

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $code . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('asset_images', $filename, 'public');
            $pathFile = $path; // Update with the new image path
        }

        // Update asset data in the database
        $updatedRow = DB::table('asset')->where('id', $id)->update([
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

        // Retrieve the asset code from the updated asset
        $asset = DB::table('asset')->where('id', $id)->first();

        if ($updatedRow) {
            return redirect()->route('assetDetails', ['id' => $asset->code])
                ->with('success', 'Asset updated successfully!');
        } else {
            return redirect()->route('assetDetails', ['id' => $asset->code])
                ->with('failed', 'Asset update failed!');
        }
    }


    public function searchFiltering(Request $request)
    {
        $search = $request->input('search');

        try {
            // Assuming you are searching the 'name' and 'code' columns
            $assets = assetModel::where('dept_ID', Auth::user()->dept_id)->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%")
                ->get();


            return response()->json($assets);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Internal Server Error', 'errorP' => $e], 500);
        }
    }

    public function delete($code)
    {
        $assetDel = assetModel::where('asset.code', $code)->get();

        $assetDel = $assetDel[0];
        // dd($assetDel[0]->image);
        // Get the path of the image from the database
        $imagePath = $assetDel->image; // assuming 'image' is the column name for the image path

        // Delete the image file from the server
        if ($imagePath && Storage::exists('public/' . $imagePath)) {
            Storage::delete('public/' . $imagePath);
        }

        // Get the path of the QR code from the database
        $qrCodePath = $assetDel->qr_img; // assuming 'qr' is the column name for the QR code path

        // Delete the QR code file from the server
        if ($qrCodePath && Storage::exists('public/' . $qrCodePath)) {
            Storage::delete('public/' . $qrCodePath);
        }

        // Delete the asset record from the database
        $assetDel->delete();

        return redirect()->route('asset')->with('success', 'Asset Deleted Successfully');
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
            ->join('users', 'users.id', '=', 'maintenance.requestor')
            ->select(
                'users.firstname as fname',
                'users.lastname as lname',
                'maintenance.cost',
                'maintenance.reason',
                'maintenance.completion_date as complete'
            )
            ->get();

        // Determine the view based on user type
        $view = ($userType == 'admin') ? 'admin.assetDetail' : 'dept_head.assetDetail';
        // Return the appropriate view with the asset data, including the QR code
        return view($view, compact('retrieveData', 'updatedCustomFields', 'department', 'categories', 'location', 'model', 'status', 'manufacturer', 'assetRet'));
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
            Log::info('Authenticated user department ID: ' . $userDept);

            foreach ($rows as $row) {
                if (count($row) < count($headers)) {
                    Log::warning('Skipping a row due to insufficient columns.', ['row' => $row]);
                    continue;
                }

                $rowData = array_combine($headers, $row);
                Log::info('Processing row data.', $rowData);

                // Create or retrieve related models
                $category = category::firstOrCreate(
                    ['name' => $rowData['category'], 'dept_ID' => $userDept],
                    ['description' => 'new item description']
                );
                Log::info('Category created or retrieved.', ['category_id' => $category->id]);

                $location = locationModel::firstOrCreate(
                    ['name' => $rowData['location']],
                    ['description' => 'new item description']
                );
                Log::info('Location created or retrieved.', ['location_id' => $location->id]);

                $manufacturer = Manufacturer::firstOrCreate(
                    ['name' => $rowData['manufacturer']],
                    ['description' => 'new item description']
                );
                Log::info('Manufacturer created or retrieved.', ['manufacturer_id' => $manufacturer->id]);

                $model = ModelAsset::firstOrCreate(
                    ['name' => $rowData['model']],
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

                // Define QR code path
                $qrCodePath = 'qrcodes/' . $assetCode . '.png';
                $qrStoragePath = storage_path('app/public/' . $qrCodePath);

                // Ensure directory exists
                if (!file_exists(storage_path('app/public/qrcodes'))) {
                    mkdir(storage_path('app/public/qrcodes'), 0777, true);
                    Log::info('Created QR codes directory.');
                }

                // Generate QR code
                \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(250)
                    ->generate($assetCode, $qrStoragePath);
                Log::info('QR code generated and saved.', ['path' => $qrCodePath]);

                try {
                    // Create the asset
                    assetModel::create([
                        'code' => $assetCode,
                        'name' => $rowData['name'],
                        'salvage_value' => (int) $rowData['salvage_value'],
                        'depreciation' => (int) $rowData['depreciation'],
                        'purchase_date' => $rowData['purchase_date'],
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
                    Log::info('Asset created successfully.', ['code' => $assetCode]);
                } catch (\Exception $e) {
                    Log::error('Error inserting asset: ' . $e->getMessage(), ['row' => $rowData]);
                }
            }

            Log::info('CSV uploaded successfully.');
            return response()->json(['success' => true, 'message' => 'CSV uploaded successfully']);
        } catch (\Throwable $th) {
            Log::error('Error during CSV upload: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => 'Error uploading CSV. Check logs.'], 500);
        }
    }

}
