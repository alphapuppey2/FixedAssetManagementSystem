<?php

namespace App\Http\Controllers;

use App\Models\assetModel;
use App\Models\category;
use App\Models\ActivityLog;
use App\Models\department;
use Illuminate\Support\Facades\Auth;
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
        $activeTab = $request->input('tab', 'model');
        $departmentID = Auth::user()->dept_id;

        $RetrieveData = NULL;
        switch ($activeTab) {
            case 'model':
                $RetrieveData = ModelAsset::where('dept_ID',$departmentID)->get();
                break;
            case 'location':
                $RetrieveData = locationModel::where('dept_ID',$departmentID)->get();
                break;
            case 'manufacturer':
                $RetrieveData = Manufacturer::where('dept_ID',$departmentID)->get();
                break;
            case 'category':
                $RetrieveData = category::where('dept_ID',$departmentID)->get();
                break;
            case 'customFields':
                $fetch = department::where('id',Auth::user()->dept_id)->get();
                $RetrieveData = json_decode($fetch[0]->custom_fields);
                break;
        }

        // dd($RetrieveData);
        return view('dept_head.setting', [
            'activeTab' => $activeTab,
            'data' => $RetrieveData,
        ]);
    }

    public function UpdateSettings(Request $request,$tab ,$id){

        $validated = $request->validate([
            'name' => 'required | string',
            'description' => ' sometimes |string',
            'helptext' => ' sometimes |string',
            'type' => ' sometimes |string',

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
            $customfields = json_decode($table->custom_fields);

            $customfields[$id]->name = $validated['name'];
            $customfields[$id]->type = $validated['type'];
            $customfields[$id]->helptext = $validated['helptext'];

            $table->custom_fields = json_encode($customfields);

            break;
        default:
            return response()->json(['success' => false, 'message' => 'Invalid tab' , 'tab' => $tab], 400);
    }

    // Ensure the table exists
    if (!$table) {
        return response()->json(['success' => false, 'error' => 'Item not found'], 404);
    }

    if($tab !== 'customFields'){
        $table->description = $validated['description'];
        $table->name = $validated['name'];
    }
    $table->save();


    return response()->json(['success' => true, 'session' => 'Setting is updated successfully']);
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
                $deleterow = json_decode($department->custom_fields);

                unset($deleterow[$id]);

                $department->custom_fields = json_encode($deleterow);

                $department->save();

                break;
            default:
            return redirect()->back()->withErrors('Failed to remove the item from the list.');
        }

        return redirect()->back()->with('Setting deleted successfully.');
    }
    public function store(Request $request ,$tab){

        $userDept = Auth::user()->dept_id;
        $validation = $request->validate([
            'nameSet' => 'required | string | max:20',
            'description' => 'sometimes | string | max:255',
            'type' => 'sometimes | string | max:255',
            'helptxt' => 'sometimes | string | max:255'
        ]);

        switch($tab){
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
                // $department = department::where('id',$userDept)->get();
                $department = department::where('id',$userDept)->first();

                // Check if the department record exists
                if (!$department) {
                    return redirect()->back()->withErrors(['errors' => 'Department not found.']);
                }

                // $customF = json_decode($department[0]->custom_fields);

                    // Initialize $customF as an array if the custom_fields column is null
                $customF = json_decode($department->custom_fields) ?? [];

                $exists = collect($customF)->contains('name', $validation['nameSet']);

                if ($exists) {
                    return redirect()->back()->withErrors(['errors' => 'The custom field name already exists.']);
                }

                // Add the new custom field at the beginning of the array
                array_unshift($customF, [
                    'name' => $validation['nameSet'],
                    'type' => $validation['type'],
                    'helptext' => $validation['helptxt']
                ]);

                // Update the department with the new custom fields JSON
                department::where('id', $userDept)->update(['custom_fields' => json_encode($customF)]);
                break;

                // if($customF === null){
                //     $customF = [];
                //     array_unshift($customF,["name" => $validation['nameSet'],
                //                      "type" => $validation['type'],
                //                      "helptext" => $validation['helptxt']]);
                // }
                // else{
                //     $collect = collect($customF);
                //     $column = $collect->contains('name' , $validation['nameSet']);



                //     if($column !== false ){
                //         return redirect()->back()->withErrors(['errors' =>'The Name already exists']);
                //     }



                //     array_unshift($customF,["name" => $validation['nameSet'],
                //                      "type" => $validation['type'],
                //                      "helptext" => $validation['helptxt']]);

                // }
                // department::where('id',$userDept)->update(['custom_fields' => json_encode($customF)]);
            default:
                return redirect()->back()->withErrors(['errors' => 'Invalid tab selection.']);
        }

        return redirect()->back()->with('session', 'Setting added successfully.');
    }


}

