<?php

namespace App\Http\Controllers;

use App\Models\assetModel;
use App\Models\category;
use App\Models\department;
use Illuminate\Support\Facades\Auth;
use App\Models\locationModel;
use App\Models\Manufacturer;
use App\Models\ModelAsset;
use Illuminate\Http\Request;

class settingController extends Controller
{
    //

    public function showSettings(Request $request)
    {
        $activeTab = $request->input('tab', 'model');


        $RetrieveData = NULL;

        switch ($activeTab) {
            case 'model':
                $RetrieveData = ModelAsset::all();
                break;
            case 'location':
                $RetrieveData = locationModel::all();
                break;
            case 'manufacturer':
                $RetrieveData = Manufacturer::all();
                break;
            case 'category':
                $RetrieveData = category::all();
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

            // return response()->json(['success' => true, 'customFields' => $table->custom_fields]);

            break;
        default:
            return response()->json(['success' => false, 'message' => 'Invalid tab' , 'tab' => $tab], 400);
    }

    // Ensure the model exists
    if (!$table) {
        return response()->json(['success' => false, 'error' => 'Item not found'], 404);
    }

    if($tab !== 'customFields'){
        $table->description = $validated['description'];
        $table->name = $validated['name'];
    }
    $table->save();
    return response()->json(['success' => true, 'session' => 'Description updated successfully']);
}

    public function destroy($tab,$id){

        $deleteFrom = null;

        switch($tab){
            case 'model':
                $deleteFrom = ModelAsset::findOrFail($id);
                if($deleteFrom->hasMany(assetModel::class, 'model_key')->exists()){
                    return redirect()->back()->withErrors('Cannot be deleted as there are linked products.')
                     ->withInput(); // Fallback URL
                }
                break;
            case 'manufacturer':
                $deleteFrom = manufacturer::findOrFail($id);
                if($deleteFrom->hasMany(assetModel::class, 'manufacturer_key')->exists()){
                    return redirect()->back()->withErrors('Cannot be deleted as there are linked products.')
                     ->withInput(); // Fallback URL
                }
                break;
            case 'location':
                $deleteFrom = locationModel::findOrFail($id);
                if($deleteFrom->hasMany(assetModel::class, 'loc_key')->exists()){
                    return redirect()->back()->withErrors('Cannot be deleted as there are linked products.')
                     ->withInput(); // Fallback URL
                }
                break;
            case 'category':
                $deleteFrom = category::findOrFail($id);
                if($deleteFrom->hasMany(assetModel::class, 'ctg_ID')->exists()){
                    return redirect()->back()->withErrors('Cannot be deleted as there are linked products.')
                     ->withInput(); // Fallback URL
                }
                break;
            default:
                dd(["message" => "errors"]);
        }


        if($deleteFrom !== null){
            $deleteFrom->delete();
            return redirect()->back()->with('Setting deleted successfully.');
        }
        else{
            dd('Errors');
            return redirect()->back()->withErrors('Setting deleted successfully.');
        }


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
                ModelAsset::create([
                    'name' => $validation['nameSet'],
                    'description' => $validation['description'],
                ]);
                break;
            case 'manufacturer':
                Manufacturer::create([
                    'name' => $validation['nameSet'],
                    'description' => $validation['description'],
                ]);
                break;
            case 'location':
                locationModel::create([
                    'name' => $validation['nameSet'],
                    'description' => $validation['description'],
                ]);
                break;
            case 'category':
                category::create([
                    'name' => $validation['nameSet'],
                    'dept_ID' => $userDept,
                    'description' => $validation['description'],
                ]);
                break;
            case 'customFields':
                $department = department::where('id',$userDept)->get();
                $customF = json_decode($department[0]->custom_fields);

                if($customF === null){
                    $customF = [];
                    array_unshift($customF,["name" => $validation['nameSet'],
                                     "type" => $validation['type'],
                                     "helptext" => $validation['helptxt']]);
                }
                else{
                    $column = array_column( $customF, 'name');

                    $index = array_search($column , $validation['nameSet']);

                    if($index !== false ){
                        return redirect()->back()->withErrors('Failed', 'Names repetitive.');
                    }



                    array_unshift($customF,["name" => $validation['nameSet'],
                                     "type" => $validation['type'],
                                     "helptext" => $validation['helptxt']]);

                }
                department::where('id',$userDept)->update(['custom_fields' => json_encode($customF)]);
        }

        return redirect()->back()->with('session', 'Setting added successfully.');
    }


}
