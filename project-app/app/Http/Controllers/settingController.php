<?php

namespace App\Http\Controllers;

use App\Models\assetModel;
use App\Models\category;
use App\Models\ActivityLog;
use App\Models\department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\locationModel;
use App\Models\Manufacturer;
use App\Models\User;
use App\Models\ModelAsset;
use Illuminate\Http\Request;

class settingController extends Controller
{
    //

    public function showSettings(Request $request)
{
    $userRole = Auth::user()->usertype;
    $activeTab = $request->input('tab', 'model');
    $allDepartments = department::All();

    if ($userRole === 'admin') {
        $departmentID = $request->input('department_id');
        if (!$departmentID) {
            // If no department is selected, you can redirect back with an error or select a default department
            $departmentID = $allDepartments->first()->id;
        }
        $selectedDepartmentID = $departmentID;
    } else {
        $departmentID = Auth::user()->dept_id;
        $selectedDepartmentID = $departmentID;
    }

    $RetrieveData = NULL;
    switch ($activeTab) {
        case 'model':
            $RetrieveData = ModelAsset::where('dept_ID', $departmentID)->get();
            break;
        case 'location':
            $RetrieveData = locationModel::where('dept_ID', $departmentID)->get();
            break;
        case 'manufacturer':
            $RetrieveData = Manufacturer::where('dept_ID', $departmentID)->get();
            break;
        case 'category':
            $RetrieveData = category::where('dept_ID', $departmentID)->get();
            break;
        case 'customFields':
            $fetch = department::where('id', $departmentID)->first();
            $RetrieveData = $fetch ? json_decode($fetch->custom_fields,true) : [];
            break;
    }

    $routePath = $userRole === 'admin' ? "admin.setting" : "dept_head.setting";
    $retDatas = $userRole === 'admin' ?
        [
            'activeTab' => $activeTab,
            'data' => $RetrieveData,
            'allDepartments' => $allDepartments,
            'selectedDepartmentID' => $selectedDepartmentID,
        ]
        :
        [
            'activeTab' => $activeTab,
            'data' => $RetrieveData,
        ];

        // dd($RetrieveData);
    return view($routePath, $retDatas);
}


public function UpdateSettings(Request $request, $tab, $id)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'sometimes|string',
            'helptext' => 'sometimes|string',
            'type' => 'sometimes|string',
        ]);

        switch ($tab) {
            case 'model':
                $table = ModelAsset::find($id);
                break;
            case 'location':
                $table = locationModel::find($id);
                break;
            case 'manufacturer':
                $table = Manufacturer::find($id);
                break;
            case 'category':
                $table = category::find($id);
                break;
            case 'customFields':
                $table = department::find(Auth::user()->dept_id);
                if (!$table) {
                    return response()->json(['success' => false, 'error' => 'Department not found'], 404);
                }

                $customfields = json_decode($table->custom_fields, true); // Decode as an associative array
                if (!isset($customfields[$id])) {
                    return response()->json(['success' => false, 'error' => 'Custom field not found'], 404);
                }

                // Update the specific custom field
                $customfields[$id]['name'] = $validated['name'];
                $customfields[$id]['type'] = $validated['type'];
                $customfields[$id]['helptext'] = $validated['helptext'];

                // Encode the updated array back to JSON and save
                $table->custom_fields = json_encode($customfields);
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid tab', 'tab' => $tab], 400);
        }

        if (!$table) {
            return response()->json(['success' => false, 'error' => 'Item not found'], 404);
        }

        if ($tab !== 'customFields') {
            $table->description = $validated['description'] ?? $table->description;
            $table->name = $validated['name'];
        }

        $table->save();

        return response()->json(['success' => true, 'session' => 'Setting is updated successfully']);
    } catch (\Exception $e) {
        // Log the error for debugging
        Log::error("UpdateSettings Error: " . $e->getMessage());

        // Return a JSON response with the error message
        return response()->json(['success' => false, 'error' => 'An error occurred. ' . $e->getMessage()], 500);
    }
}


    public function destroy($tab,$id){

        $deleteFrom = null;

        switch($tab){
            case 'model':
                $deleteFrom = ModelAsset::findOrFail($id);
                if($deleteFrom->hasMany(assetModel::class, 'model_key')->exists()){
                    return redirect()->back()->withErrors("Deletion not allowed due to linked products.")
                     ->withInput(); // Fallback URL
                }
                break;
            case 'manufacturer':
                $deleteFrom = manufacturer::findOrFail($id);
                if($deleteFrom->hasMany(assetModel::class, 'manufacturer_key')->exists()){
                    return redirect()->back()->withErrors("Deletion not allowed due to linked products.")
                     ->withInput(); // Fallback URL
                }
                break;
            case 'location':
                $deleteFrom = locationModel::findOrFail($id);
                if($deleteFrom->hasMany(assetModel::class, 'loc_key')->exists()){
                    return redirect()->back()->withErrors("Deletion not allowed due to linked products.")
                     ->withInput(); // Fallback URL
                }
                break;
            case 'category':
                $deleteFrom = category::findOrFail($id);
                if($deleteFrom->hasMany(assetModel::class, 'ctg_ID')->exists()){
                    return redirect()->back()->withErrors("Deletion not allowed due to linked products.")
                     ->withInput(); // Fallback URL
                }
                break;
            case 'customFields':
                $department = department::findOrFail(Auth::user()->dept_id);
                $deleterow = json_decode($department->custom_fields,true);

                unset($deleterow[$id]);

                $department->custom_fields = json_encode($deleterow);

                $department->save();

                break;
            default:
            return redirect()->back()->withErrors('Failed to remove the item from the list.');
        }

        if($tab !== 'customFields'){
            if($deleteFrom->delete()){
               Log::Info("Successfully Deleted");
            }
        }

        return redirect()->back()->with('toast','Setting deleted successfully.');

    }
    public function store(Request $request, $tab , $department_id = null)
    {
        // Check user role and determine department ID
        $userRole = Auth::user()->usertype;
        $userDept = $userRole === 'admin' ? $department_id  : Auth::user()->dept_id;


        // Validation for department ID if user is admin
        // dd($userRole === 'admin' , !$userDept , $request->input());
        if ($userRole === 'admin' && !$userDept) {
            return redirect()->back()->withErrors(['errors' => 'Department selection is required for admin.']);
        }

        // Validation for other fields
        $validation = $request->validate([
            'nameSet' => 'required|string|max:20',
            'description' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'helptxt' => 'sometimes|string|max:255'
        ]);

        // dd($validation);

        // Handle creation based on the active tab
        switch ($tab) {
            case 'model':
                if (ModelAsset::where('name', $validation['nameSet'])->where('dept_ID', $userDept)->exists()) {
                    return redirect()->back()->withErrors(['errors' => 'The model name already exists.']);
                }
                ModelAsset::create([
                    'name' => $validation['nameSet'],
                    'description' => $validation['description'],
                    'dept_ID' => $userDept
                ]);
                break;

            case 'manufacturer':
                if (Manufacturer::where('name', $validation['nameSet'])->where('dept_ID', $userDept)->exists()) {
                    return redirect()->back()->withErrors(['errors' => 'The manufacturer name already exists.']);
                }
                Manufacturer::create([
                    'name' => $validation['nameSet'],
                    'description' => $validation['description'],
                    'dept_ID' => $userDept
                ]);
                break;

            case 'location':
                if (locationModel::where('name', $validation['nameSet'])->where('dept_ID', $userDept)->exists()) {
                    return redirect()->back()->withErrors(['errors' => 'The location name already exists.']);
                }
                locationModel::create([
                    'name' => $validation['nameSet'],
                    'description' => $validation['description'],
                    'dept_ID' => $userDept
                ]);
                break;

            case 'category':
                if (category::where('name', $validation['nameSet'])->where('dept_ID', $userDept)->exists()) {
                    return redirect()->back()->withErrors(['errors' => 'The category name already exists.']);
                }
                category::create([
                    'name' => $validation['nameSet'],
                    'dept_ID' => $userDept,
                    'description' => $validation['description'],
                ]);
                break;

                case 'customFields':
                    // Find department based on user's department ID or admin-selected department ID
                    $department = department::where('id', $userDept)->first();

                    if (!$department) {
                        Log::error('Department not found.');
                        return redirect()->back()->withErrors(['errors' => 'Department not found.']);
                    }

                    // Decode existing custom fields, or initialize as an empty array if null
                    $customF = json_decode($department->custom_fields, true) ?? [];

                    // Check if custom field name already exists
                    $exists = collect($customF)->contains('name', $validation['nameSet']);
                    if ($exists) {
                        Log::error('The custom field name already exists.');
                        return redirect()->back()->withErrors(['errors' => 'The custom field name already exists.']);
                    }

                    // Add the new custom field at the beginning of the array
                    array_unshift($customF, [
                        'name' => $validation['nameSet'],
                        'type' => $validation['type'],
                        'helptext' => $validation['helptxt']
                    ]);

                    // Encode the updated custom fields array as JSON
                    $jsonCustomF = json_encode($customF);

                    // Check JSON encoding output and handle empty array
                    if ($jsonCustomF === false) {
                        Log::error('Failed to encode custom fields to JSON.');
                        return redirect()->back()->withErrors(['errors' => 'Failed to encode custom fields to JSON.']);
                    }
                    Log::info('JSON encoded custom fields:', ['jsonCustomF' => $jsonCustomF]);

                    // Update the department with the new custom fields JSON using save() instead of update()
                    $department->custom_fields = $jsonCustomF;
                    $updateResult = $department->save();

                    if ($updateResult) {
                        Log::info('Updated department custom_fields:', ['custom_fields' => $department->custom_fields]);
                    } else {
                        Log::error('Failed to save custom fields.');
                        return redirect()->back()->withErrors(['errors' => 'Failed to save custom fields.']);
                    }
                    break;

            default:
                return redirect()->back()->withErrors(['errors' => 'Invalid tab selection.']);
        }

        return redirect()->back()->with('session', 'Setting added successfully.');
    }
}

