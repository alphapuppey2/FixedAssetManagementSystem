<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\department;
use App\Models\assetModel;
use App\Models\Maintenance;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\HttpFoundation\StreamedResponse;

class AsstController extends Controller
{
    public function showAllAssets(){
        $assets = DB::table('asset')
                    ->join('department', 'asset.dept_ID', '=', 'department.id')
                    ->join('category', 'asset.ctg_ID', '=', 'category.id')
                    ->select('asset.id', 'asset.code' , 'asset.name', 'asset.image', 'asset.cost', 'asset.salvageVal', 'asset.depreciation', 'asset.usage_Lifespan', 'asset.status', 'category.name as category', 'department.name as department')
                    ->orderBy('asset.code', 'asc')
                    ->paginate(10);
    
        return view("admin.assetList", compact('assets'));
    }

    public function showAssetsByDept($dept = null){
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

    public function searchAssets(Request $request){
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

    public function showDeptAsset(){
        $userDept = Auth::user()->dept_id;


        $asset = DB::table('asset')
                        ->join('department', 'asset.dept_ID', '=', 'department.id')
                        ->join('category', 'asset.ctg_ID', '=', 'category.id')
                        ->where('asset.dept_ID', $userDept)
                        ->select('asset.id', 'asset.code' , 'asset.name' ,'asset.image' ,'asset.cost' ,'asset.salvageVal' ,'asset.depreciation' ,'asset.usage_Lifespan','asset.status', 'category.name as category', 'department.name as department')
                        ->orderBy('asset.code', 'asc')
                        ->paginate(5);


        // dd($asset);

        return view("dept_head.asset" ,compact('asset'));
    }

    public function showHistory($id){
        //history of a Asset
        $asset = AssetModel::where('asset.id', $id)
                                    ->select("asset.code as assetCode")->first();
        $AssetMaintenance = Maintenance::where("asset_key", $id)->get();


        dd($AssetMaintenance);
        dd($asset);
        return view('dept_head.MaintenanceHistory' , compact('AssetMaintenance','asset'));
    }
    public function showForm(){

        $usrDPT = Auth::user()->dept_id;

        $departments = array('list' => DB::table('department')->get());
        $categories = array('ctglist' => DB::table('category')->where('dept_ID', $usrDPT)->get());
        $location = array('locs' => DB::table('location')->get());
        $model = array('mod' => DB::table('model')->get());
        $manufacturer = array('mcft' => DB::table('manufacturer')->get());



        return view('dept_head.createAsset',compact('departments' , 'categories','location' ,'model','manufacturer'));
    }

    public  function convertJSON($key , $value){

        $additionalInfo = [];

        // Initialize an empty array to hold key-value pairs
        if(isset($key) && isset($value)){
            foreach($key as $index => $keys){
                if (!empty($key) && !empty($value[$index])) {
                    $additionalInfo[$keys] = $value[$index];
                }
            }
        }
        return json_encode($additionalInfo);
    }

    public function create(Request $request){
        $userDept = Auth::user()->dept_id;
        // dd($request);
        if(!$request->validate([
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif',
            'assetname'=> 'required',
            'category'=> 'required',
            'cost'=>'required|numeric|min:0.01',
            'salvageVal'=> 'required|numeric|min:0.01',
            'purchased' => 'required | date',
            'usage' => 'required',
            'loc'=> 'required',
            'mod'=> 'required',
            'mcft'=> 'required',
            'field.key.*' => 'nullable|string|max:255',
            'field.value.*' => 'nullable|string|max:255',

        ])){
            return redirect()->back()->withError();
        }

        //additional Fields

        $customFields = $this->convertJSON($request->input('field.key'), $request->input('field.value'));

        //code
        $department = DB::table('department')->where('id',$userDept)->get();

        $departmentCode = $department[0]->name;
        $lastID =  department::where('name',$departmentCode)->max('assetSequence');
        $seq = $lastID ? $lastID + 1 : 1;
        $code = $departmentCode.'-'.str_pad($seq, 4, '0', STR_PAD_LEFT);
        //image
        $pathFile = NULL;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $code.'-'.time().'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs('images', $filename,'public');
            $pathFile = $path;
        }
        department::where('id',$userDept)->increment('assetSequence',1);

        //depreciation method == Straight Line
        $depreciation = ($request->cost - $request->salvageVal) / $request->usage;


        DB::table('asset')->insert([
            'image'=>$pathFile,
            'name' => $request->assetname,
            'cost' => $request->cost,
            'code' => $code,
            'purchase_date' => $request->purchased,
            'ctg_ID' => $request->category,
            'depreciation'=>$depreciation,
            'salvageVal'=>$request->salvageVal,
            'usage_Lifespan'=>$request->usage,
            'custom_fields' =>$customFields,
            'dept_ID' => $userDept,
            'loc_key' => $request->loc,
            'model_key' => $request->mod,
            'manufacturer_key' => $request->mcft,
            'created_at'=>now(),
        ]);

        return redirect()->to('/asset')->with('success' , 'New Asset Created');
    }

    public static function assetCount(){
        //dashboard
        $userDept = Auth::user()->dept_id;

        $asset['active'] = DB::table('asset')->where('asset.status','=' , 'active')
                                             ->where("asset.dept_ID","=", $userDept)->count();
        $asset['um'] = DB::table('asset')->where('status','=' , 'under maintenance')
                                         ->where("asset.dept_ID","=", $userDept)->count();
        $asset['dispose'] = DB::table('asset')->where('status','=' , 'dispose')
                                              ->where("asset.dept_ID","=", $userDept)->count();
        $asset['deploy'] = DB::table('asset')->where('status','=' , 'deployed')
                                             ->where("asset.dept_ID","=", $userDept)->count();

        //FOR DASHBOARD CARDS
        return view('dept_head.Home' , ['asset' => $asset]);

    }

    public function update(Request $request , $id){

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
            $department = DB::table('department')->where('id',$userDept)->get();

            $departmentCode = $department[0]->name;
            $lastID =  department::where('name',$departmentCode)->max('assetSequence');
            $seq = $lastID ? $lastID + 1 : 1;
            $code = $departmentCode.'-'.str_pad($seq, 4, '0', STR_PAD_LEFT);

            $fieldUpdate = $this->convertJSON($request->input('field.key'), $request->input('field.value'));


            //image
            $pathFile = NULL;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $code.'-'.time().'.'.$image->getClientOriginalExtension();
                $path = $image->storeAs('images', $filename,'public');
                $pathFile = $path;
            }
               $updatedRow = DB::table('asset')->where('id',$id)->update([
                                    'image' => $pathFile,
                                    'name' => $validatedData["name"],
                                    'cost' => $validatedData["cost"],
                                    'ctg_ID' => $validatedData["category"],
                                    'manufacturer_key' => $validatedData['mcft'],
                                    'model_key' => $validatedData["mod"],
                                    'loc_key' => $validatedData["loc"],
                                    'usage_Lifespan' => $validatedData["usage"],
                                    'status'=>$validatedData["status"],
                                    'custom_fields' => $fieldUpdate,
                                    'updated_at' =>now(),
                ]);

                if($updatedRow){
                    return redirect()->route("asset")->with('success', 'Asset updated successfully!');
                }
                else{
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

            return response()->json(['error' => 'Internal Server Error', 'errorP'=> $e], 500);
        }
    }
    public function delete($id){

        $assetDel = assetModel::findOrFail($id);

         // Get the path of the image from the database
        $imagePath = $assetDel->image_path; // assuming 'image_path' is the column name

        // Delete the image file from the server
        if ($imagePath && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        // Delete the row from the database
        $assetDel->delete();


        return redirect()->route('asset')->with('success','Asset Deleted Successfully');
    }

    // public function showDetails($id){
    //     $userDept = Auth::user()->dept_id;

    //     $department = array('list' => DB::table('department')->get());
    //     $categories = array('ctglist' => DB::table('category')->where('dept_ID', $userDept)->get());
    //     $location = array('locs' => DB::table('location')->get());
    //     $model = array('mod' => DB::table('model')->get());
    //     $manufacturer = array('mcft' => DB::table('manufacturer')->get());
    //     $status = array('sts' =>['active' ,'deployed' , 'need repair' , 'under maintenance', 'dispose']);

    //     //$id is for asset code ...

    //     $retrieveData = assetModel::where('asset.id' , $id)->where('asset.dept_ID' , Auth::user()->dept_id)
    //                                 ->join('department' , 'asset.dept_id' , '=', 'asset.dept_ID')
    //                                 ->join('category','asset.ctg_ID' , '=','category.id')
    //                                 ->join('model','asset.model_key' , '=','model.id')
    //                                 ->join('manufacturer','asset.manufacturer_key' , '=','manufacturer.id')
    //                                 ->join('location','asset.loc_key' , '=','location.id')
    //                                 ->select(
    //                                     'asset.id',
    //                                     'asset.depreciation',
    //                                     'asset.image',
    //                                     'asset.name',
    //                                     'asset.code',
    //                                     'asset.cost',
    //                                     'asset.salvageVal',
    //                                     'asset.usage_Lifespan',
    //                                     'asset.status',
    //                                     'asset.custom_fields',
    //                                     'asset.created_at',
    //                                     'asset.updated_at',
    //                                     'category.name as category',
    //                                     'model.name as model',
    //                                     'location.name as location',
    //                                     'manufacturer.name as manufacturer',
    //                                     )
    //                                 ->get();
    //     $fields = json_decode($retrieveData[0]->custom_fields,true);
        
    //     return view('dept_head.assetDetail' , compact('retrieveData' , 'fields','department','categories','location','model','status','manufacturer'));
    // }

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

    // Determine the view based on user type
    $view = $userType == 'admin' ? 'admin.assetDetail' : 'dept_head.assetDetail';

    // Return the appropriate view with the asset data
    return view($view, compact('retrieveData', 'fields', 'department', 'categories', 'location', 'model', 'status', 'manufacturer'));
}

    


    public function showRequestList() {
        // Fetch requests using the DB facade
        $requests = DB::table('request')->where('request.requestor','=',Auth::user()->id)
                                        ->join('asset','asset.id' , '=', 'request.asset_id')
                                        ->join('department','department.id' , '=', 'asset.dept_ID')
                                        ->join('category','category.id' , '=', 'asset.ctg_ID')
                                        ->join('location','location.id' , '=', 'asset.loc_key')
                                        ->join('model','model.id' , '=', 'asset.model_key')
                                        ->select(
                                            'asset.name',
                                            'asset.id as asset_id', //remove nalang ni siya
                                            'asset.image',
                                            'asset.code',
                                            'asset.cost',
                                            'asset.depreciation',
                                            'asset.salvageVal',
                                            'asset.usage_Lifespan',
                                            'asset.status',
                                            'asset.custom_fields',
                                            'asset.updated_at as assetCreated',
                                            'asset.created_at as assetEdited',
                                            'category.name as category',
                                            'model.name as model',
                                            'location.name as location',
                                            'department.name as department',
                                            'request.Description',
                                            'request.id',
                                            'request.status',
                                            'request.requestor',
                                            'request.approvedBy',
                                            'request.created_at',
                                            'request.updated_at',
                                    )->get();

        // Debugging the query output
        if ($requests->isEmpty()) {
            dd('No requests found in the database.');
        }

        // Pass the requests data to the view
        return view('user.requestList', compact('requests'));
    }

    public function downloadCsvTemplate(){
        // Get the column names from the 'asset' table
        $columns = Schema::getColumnListing('asset');

        // Convert the column names into a CSV-friendly format
        $csvContent = implode(",", $columns) . "\n";

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="asset_template.csv"');
    }
}
