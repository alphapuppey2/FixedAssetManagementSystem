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
        $activeTab = $request->input('tab', 'model'); // Default to 'posts' if no tab is specified


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
}
