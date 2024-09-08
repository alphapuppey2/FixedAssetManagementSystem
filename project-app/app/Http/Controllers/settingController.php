<?php

namespace App\Http\Controllers;

use App\Models\category;
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

    public function UpdateSettings(Request $request,$tab ,$id)
{


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
        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }

    // Update the description
    $table->description = $request->input('description');
    $table->save();

    return response()->json(['success' => true, 'session' => 'Description updated successfully']);
}


}
