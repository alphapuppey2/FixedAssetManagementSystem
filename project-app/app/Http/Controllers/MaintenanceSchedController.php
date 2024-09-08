<?php

namespace App\Http\Controllers;

use App\Models\Predictive;
use App\Models\Preventive;
use App\Models\assetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceSchedController extends Controller
{
    public function showPreventive(Request $request)
    {
        // Get the logged-in user's department ID
        $userDeptId = Auth::user()->dept_id;

        // Get the number of rows to display from the request, default to 10
        $perPage = $request->input('rows_per_page', 10);

        // Fetch preventive maintenance data where the asset belongs to the user's department
        $preventives = Preventive::whereHas('asset', function ($query) use ($userDeptId) {
            $query->where('dept_ID', $userDeptId);
        })->with('asset')->paginate($perPage);

        return view('dept_head.maintenance_sched', [
            'tab' => 'preventive',
            'records' => $preventives,
            'perPage' => $perPage,
        ]);
    }

    public function showPredictive(Request $request)
    {
        // Get the logged-in user's department ID
        $userDeptId = Auth::user()->dept_id;

        // Get the number of rows to display from the request, default to 10
        $perPage = $request->input('rows_per_page', 10);

        // Fetch predictive maintenance data where the asset belongs to the user's department
        $predictives = Predictive::whereHas('asset', function ($query) use ($userDeptId) {
            $query->where('dept_ID', $userDeptId);
        })->with('asset')->paginate($perPage);

        return view('dept_head.maintenance_sched', [
            'tab' => 'predictive',
            'records' => $predictives,
            'perPage' => $perPage,
        ]);
    }
}
