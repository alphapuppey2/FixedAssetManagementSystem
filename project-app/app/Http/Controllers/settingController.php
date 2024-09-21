<?php

namespace App\Http\Controllers;

use App\Models\assetModel;
use App\Models\category;
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
        }

        return view('dept_head.setting', [
            'activeTab' => $activeTab,
            'data' => $RetrieveData,
        ]);
    }

    public function UpdateSettings(Request $request,$tab ,$id){

        $validated = $request->validate([
            'description' => ' required|string'
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
        default:
            return response()->json(['success' => false, 'message' => 'Invalid tab'], 400);
    }

    // Ensure the model exists
    if (!$table) {
        return response()->json(['success' => false, 'error' => 'Item not found'], 404);
    }

    // Update the description
    $table->description = $validated['description'];
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
            'description' => 'required | string | max:255'
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
        }

        return redirect()->back()->with('session', 'Setting added successfully.');
    }


}
