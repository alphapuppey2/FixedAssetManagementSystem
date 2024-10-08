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
use LaravelDaily\LaravelCharts\Classes\LaravelChart;



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
            ->select('asset.id', 'asset.code', 'asset.name', 'asset.image', 'asset.cost', 'asset.salvageVal', 'asset.depreciation', 'asset.usage_Lifespan', 'asset.status', 'category.name as category', 'department.name as department')
            ->orderBy('asset.code', 'asc')
            ->paginate(10);

        return view("admin.assetList", compact('assets'));
    }

    public function showAssetsByDept($dept = null)
    {
        $query = DB::table('asset')
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->select('asset.id', 'asset.code', 'asset.name', 'asset.image', 'asset.cost', 'asset.salvageVal', 'asset.depreciation', 'asset.usage_Lifespan', 'asset.status', 'category.name as category', 'department.name as department')
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
            ->select('asset.id', 'asset.code', 'asset.name', 'asset.image', 'asset.cost', 'asset.salvageVal', 'asset.depreciation', 'asset.usage_Lifespan', 'asset.status', 'category.name as category', 'department.name as department')
            ->orderBy('asset.code', 'asc')
            ->paginate(10);


        // dd($asset);

        return view("dept_head.asset", compact('asset'));
    }

    public function showHistory($id)
    {
        //history of a Asset
        $asset = AssetModel::where('asset.id', $id)
                                    ->select("asset.code as assetCode")->first();

        $assetRet = Maintenance::where("asset_key" , $id)
                                    ->where("completed" , 1)
                                    ->join('users' ,'users.id','=' , 'maintenance.requestor')
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
        $department = department::find( $usrDPT);


            $categories = array('ctglist' => DB::table('category')->where('dept_ID', $usrDPT)->get());
            $location = array('locs' => DB::table('location')->where('dept_ID', $usrDPT)->get());
            $model = array('mod' => DB::table('model')->where('dept_ID', $usrDPT)->get());
            $manufacturer = array('mcft' => DB::table('manufacturer')->where('dept_ID', $usrDPT)->get());
            $addInfos = json_decode($department->custom_fields);


            if($categories['ctglist']->isEmpty() || $location['locs']->isEmpty() || $model['mod']->isEmpty() || $manufacturer['mcft']->isEmpty()){
                return redirect()->back()->with('failed', 'pag butang sa Setting bago ka mu add!');
            }

        return view('dept_head.createAsset',compact('addInfos' , 'categories','location' ,'model','manufacturer'));
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
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif',
        'assetname' => 'required',
        'category' => 'required',
        'cost' => 'required|numeric|min:0.01',
        'salvageVal' => 'required|numeric|min:0.01',
        'purchased' => 'required|date',
        'usage' => 'required',
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
        $path = $image->storeAs('images', $filename, 'public');
        $pathFile = $path;
    }

    // Increment asset sequence in department
    department::where('id', $userDept)->increment('assetSequence', 1);

    // Calculate depreciation (Straight Line method)
    $depreciation = ($request->cost - $request->salvageVal) / $request->usage;

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
        'image' => $pathFile,
        'name' => $request->assetname,
        'cost' => $request->cost,
        'code' => $code,
        'purchase_date' => $request->purchased,
        'ctg_ID' => $request->category,
        'depreciation' => $depreciation,
        'salvageVal' => $request->salvageVal,
        'usage_Lifespan' => $request->usage,
        'custom_fields' => $customFields,
        'dept_ID' => $userDept,
        'loc_key' => $request->loc,
        'model_key' => $request->mod,
        'manufacturer_key' => $request->mcft,
        'qr' => $qrCodePath,  // Store the path to the QR code image file
        'created_at' => now(),
    ]);

    return redirect()->to('/asset')->with('success', 'New Asset Created');
}

    public static function assetCount(){
    // Dashboard
    $userDept = Auth::user()->dept_id;

    $asset['active'] = DB::table('asset')->where('asset.status', '=', 'active')
        ->where("asset.dept_ID", "=", $userDept)->count();
    $asset['um'] = DB::table('asset')->where('status', '=', 'under maintenance')
        ->where("asset.dept_ID", "=", $userDept)->count();
    $asset['dispose'] = DB::table('asset')->where('status', '=', 'dispose')
        ->where("asset.dept_ID", "=", $userDept)->count();
    $asset['deploy'] = DB::table('asset')->where('status', '=', 'deployed')
        ->where("asset.dept_ID", "=", $userDept)->count();

    $newAssetCreated = assetModel::where('dept_ID', $userDept)
        ->whereMonth('created_at', Carbon::now()->month)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    // Initialize an array for months from four months ago up to the current month
    $monthsActive = [];
    $monthsUnderMaintenance = [];
    for ($i = 4; $i >= 0; $i--) { // Loop backwards from 4 months ago to the current month
        $date = Carbon::now()->subMonths($i);
        $monthName = $date->format('M');
        $year = $date->format('Y');
        $monthsActive["$monthName $year"] = 0; // Initialize count to 0 for Active
        $monthsUnderMaintenance["$monthName $year"] = 0; // Initialize count to 0 for Under Maintenance
    }

    // Retrieve data for Active assets grouped by month and year
    $dataActive = assetModel::where('dept_ID', Auth::user()->dept_id)
        ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), [
            Carbon::now()->subMonths(4)->format('Y-m'), // Start from 4 months ago
            Carbon::now()->format('Y-m') // Up to the current month
        ])
        ->where('status', 'active')
        ->select(
            DB::raw('DATE_FORMAT(created_at, "%b %Y") as monthYear'), // Get the month and year
            DB::raw('COUNT(*) as count') // Count the records for that month
        )
        ->groupBy('monthYear')
        ->get();

    // Retrieve data for Under Maintenance assets grouped by month and year
    $dataUnderMaintenance = assetModel::where('dept_ID', Auth::user()->dept_id)
        ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), [
            Carbon::now()->subMonths(4)->format('Y-m'), // Start from 4 months ago
            Carbon::now()->format('Y-m') // Up to the current month
        ])
        ->where('status', 'under maintenance')
        ->select(
            DB::raw('DATE_FORMAT(created_at, "%b %Y") as monthYear'), // Get the month and year
            DB::raw('COUNT(*) as count') // Count the records for that month
        )
        ->groupBy('monthYear')
        ->get();

    // Map the retrieved data to the $monthsActive array
    foreach ($dataActive as $record) {
        $monthsActive[$record->monthYear] = $record->count; // Update the month count for Active assets
    }

    // Map the retrieved data to the $monthsUnderMaintenance array
    foreach ($dataUnderMaintenance as $record) {
        $monthsUnderMaintenance[$record->monthYear] = $record->count; // Update the month count for Under Maintenance assets
    }

    // FOR DASHBOARD CARDS
    return view('dept_head.home', [
        'asset' => $asset,
        'newAssetCreated' => $newAssetCreated,
        'Amonths' => array_keys($monthsActive), // Array of month labels
        'Acounts' => array_values($monthsActive), // Array of counts for Active assets
        'UMmonths' => array_keys($monthsUnderMaintenance), // Array of month labels for Under Maintenance
        'UMcounts' => array_values($monthsUnderMaintenance), // Array of counts for Under Maintenance assets
    ]);


    }

    public function update(Request $request, $id)
    {

        $userDept = Auth::user()->dept_id;

        // dd($request);
        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'name' => 'required|string',
            'cost' => 'required|numeric',
            'depreciation' => 'required|numeric',
            'category' => 'required|exists:category,id',
            'usage' => 'required|numeric',
            'mod' => 'required|string',
            'mcft' => 'required|exists:manufacturer,id',
            'loc' => 'required|exists:location,id',
            'status' => 'required|nullable|string|max:511',
            'field.key.*' => 'nullable|string|max:255',
            'field.value.*' => 'nullable|string|max:255',
        ]);

        //code for image fileName
        $department = DB::table('department')->where('id', $userDept)->get();

        $departmentCode = $department[0]->name;
        $lastID =  department::where('name', $departmentCode)->max('assetSequence');
        $seq = $lastID ? $lastID + 1 : 1;
        $code = $departmentCode . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

        $fieldUpdate = $this->convertJSON($request->input('field.key'), $request->input('field.value'));


        //image
        $pathFile = NULL;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $code . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $filename, 'public');
            $pathFile = $path;
        }
        $updatedRow = DB::table('asset')->where('id', $id)->update([
            'image' => $pathFile,
            'name' => $validatedData["name"],
            'cost' => $validatedData["cost"],
            'ctg_ID' => $validatedData["category"],
            'manufacturer_key' => $validatedData['mcft'],
            'model_key' => $validatedData["mod"],
            'loc_key' => $validatedData["loc"],
            'usage_Lifespan' => $validatedData["usage"],
            'status' => $validatedData["status"],
            'custom_fields' => $fieldUpdate,
            'updated_at' => now(),
        ]);

        if ($updatedRow) {
            return redirect()->route("asset")->with('success', 'Asset updated successfully!');
        } else {
            return redirect()->route("asset")->with('failed', 'Asset update Failed!');
        }
    }

    public function searchFiltering(Request $request)
    {
        $search = $request->input('search');

        try {
            // Assuming you are searching the 'name' and 'code' columns
            $assets = assetModel::where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%")
                ->get();


            return response()->json($assets);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Internal Server Error', 'errorP' => $e], 500);
        }
    }
    public function delete($id)
    {
        $assetDel = assetModel::findOrFail($id);

        // Get the path of the image from the database
        $imagePath = $assetDel->image; // assuming 'image' is the column name for the image path

        // Delete the image file from the server
        if ($imagePath && Storage::exists('public/' . $imagePath)) {
            Storage::delete('public/' . $imagePath);
        }

        // Get the path of the QR code from the database
        $qrCodePath = $assetDel->qr; // assuming 'qr' is the column name for the QR code path

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
    public function showDetails($id)
    {
        // Get the logged-in user's department ID and user type
        $userDept = Auth::user()->dept_id;
        $userType = Auth::user()->usertype;

        // Retrieve necessary data from related tables
        $department = ['list' => DB::table('department')->get()];
        $categories = ['ctglist' => DB::table('category')->when($userType != 'admin', function ($query) use ($userDept) {
            return $query->where('dept_ID', $userDept);
        })->get()];
        $location = ['locs' => DB::table('location')->get()];
        $model = ['mod' => DB::table('model')->get()];
        $manufacturer = ['mcft' => DB::table('manufacturer')->get()];
        $status = ['sts' => ['active', 'deployed', 'need repair', 'under maintenance', 'dispose']];

        // Build the query to retrieve the asset data based on the asset ID
        $retrieveDataQuery = assetModel::where('asset.id', $id)
            ->join('department', 'asset.dept_id', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->join('model', 'asset.model_key', '=', 'model.id')
            ->join('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->join('location', 'asset.loc_key', '=', 'location.id')
            ->select(
                'asset.id',
                'asset.depreciation',
                'asset.image',
                'asset.name',
                'asset.code',
                'asset.cost',
                'asset.salvageVal',
                'asset.usage_Lifespan',
                'asset.status',
                'asset.custom_fields',
                'asset.qr',  // Add the QR code path
                'asset.created_at',
                'asset.updated_at',
                'category.name as category',
                'model.name as model',
                'location.name as location',
                'manufacturer.name as manufacturer'
            );

        // If the user is not an admin, filter by dept_ID
        if ($userType != 'admin') {
            $retrieveDataQuery->where('asset.dept_ID', '=', $userDept);
        }

        // Retrieve the asset data
        $retrieveData = $retrieveDataQuery->first();

        // If no asset is found, redirect with an error message
        if (!$retrieveData) {
            // Check the user type and redirect accordingly
            if ($userType == 'admin') {
                return redirect()->route('assetList')->with('error', 'Asset not found.');
            } else {
                return redirect()->route('asset')->with('error', 'Asset not found.');
            }
        }

        // Decode the custom fields
        $fields = json_decode($retrieveData->custom_fields, true);

        $thisDepartment = $department['list']->firstWhere('name', "IT");



        // Determine the view based on user type
        $view = $userType == 'admin' ? 'admin.assetDetail' : 'dept_head.assetDetail';

        // Return the appropriate view with the asset data, including the QR code
        return view($view, compact('retrieveData', 'fields', 'department', 'categories', 'location', 'model', 'status', 'manufacturer'));
    }

    public function downloadCsvTemplate()
    {
        // Define the readable column names that will replace foreign keys
        $columns = [
            'name',            // Asset name
            'purchase_date',   // Purchase date (YYYY-MM-DD format)
            'cost',            // Cost of the asset
            'depreciation',    // Depreciation value
            'salvageVal',      // Salvage value after depreciation
            'usage_Lifespan',  // Usage lifespan in years (can be null if unknown)
            'category',        // Asset category name (e.g., IT Equipment)
            'manufacturer',    // Manufacturer name (e.g., Sony)
            'model',           // Model name (e.g., Model X)
            'location',        // Location name (e.g., HQ)
            'status'           // Asset status (e.g., active, deployed, need Repair)
        ];

        // Add a row with sample data that matches required fields
        $sampleData = [
            'Sample Asset',    // Asset name
            now()->format('Y-m-d'),  // Purchase date (today's date in correct format)
            '10000',           // Cost in decimal (e.g., 10000.00)
            '500',             // Depreciation value
            '1000',            // Salvage value
            '10',              // Usage lifespan (e.g., 10 years)
            'IT Equipment',    // Example category
            'Sony',            // Example manufacturer
            'Model X',         // Example model
            'HQ',              // Example location
            'active'           // Status (can be: active, deployed, need Repair, under Maintenance)
        ];

        // Convert the column names and sample data into CSV format
        $csvContent = implode(",", $columns) . "\n";
        $csvContent .= implode(",", $sampleData) . "\n"; // Add a sample row

        // Return the response to download the CSV
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="asset_template.csv"');
    }



    public function uploadCsv(Request $request)
    {
        try {
            $validated = $request->validate([
                'headers' => 'required|array',
                'rows' => 'required|array',
            ]);

            $headers = $request->input('headers');
            $rows = $request->input('rows');

            if (!$rows || count($rows) == 0) {
                return response()->json(['success' => false, 'message' => 'No rows provided.'], 400);
            }

            $userDept = Auth::user()->dept_id;

            foreach ($rows as $row) {
                if (count($row) < count($headers)) {
                    continue;
                }

                $rowData = array_combine($headers, $row);

                $category = category::firstOrCreate(
                    ['name' => $rowData['category'], 'dept_ID' => $userDept],
                    ['description' => 'new item description']
                );
                $location = locationModel::firstOrCreate(
                    ['name' => $rowData['location']],
                    ['description' => 'new item description']
                );
                $manufacturer = Manufacturer::firstOrCreate(
                    ['name' => $rowData['manufacturer']],
                    ['description' => 'new item description']
                );
                $model = ModelAsset::firstOrCreate(
                    ['name' => $rowData['model']],
                    ['description' => 'new item description']
                );

                $department = DB::table('department')->where('id', $userDept)->first();
                $departmentCode = $department->name;
                $lastID = department::where('name', $departmentCode)->max('assetSequence');
                $seq = $lastID ? $lastID + 1 : 1;
                $assetCode = $departmentCode . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
                department::where('id', $userDept)->increment('assetSequence', 1);

                $qrCodePath = 'qrcodes/' . $assetCode . '.png';  // Path to store the QR code
                $qrStoragePath = storage_path('app/public/' . $qrCodePath);

                // Ensure the directory exists
                if (!file_exists(storage_path('app/public/qrcodes'))) {
                    mkdir(storage_path('app/public/qrcodes'), 0777, true);
                }

                // Generate the QR code
                \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(250)
                    ->generate($assetCode, $qrStoragePath);

                try {
                    // Create the asset
                    assetModel::create([
                        'code' => $assetCode,
                        'name' => $rowData['name'],
                        'salvageVal' => $rowData['salvageVal'],
                        'depreciation' => $rowData['depreciation'],
                        'purchase_date' => $rowData['purchase_date'],
                        'cost' => $rowData['cost'],
                        'ctg_ID' => $category->id,
                        'manufacturer_key' => $manufacturer->id,
                        'model_key' => $model->id,
                        'loc_key' => $location->id,
                        'dept_ID' => $userDept,
                        'status' => $rowData['status'] ?? 'active',
                        'qr' => $qrCodePath,  // Store the path to the QR code image file
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error inserting asset: ' . $e->getMessage());
                }
            }

            return response()->json(['success' => true, 'message' => 'CSV uploaded successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Error uploading CSV. Check logs.'], 500);
        }
    }
}
