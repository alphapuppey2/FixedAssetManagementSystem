<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\assetModel;
use App\Models\Maintenance;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('query');
        $userType = Auth::user()->usertype;
        $deptId = Auth::user()->dept_id; // Get the user's department ID

        // Initialize result collections
        $users = collect();
        $assets = collect();
        $maintenanceRecords = collect();

        // Admin Search: Search all tables and departments
        if ($userType === 'admin') {
            // Admin logic: Search all assets and maintenance records
            $users = User::where('firstname', 'LIKE', "%$query%")
                ->orWhere('lastname', 'LIKE', "%$query%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->orWhere('employee_id', 'LIKE', "%$query%")
                ->get();

            $assets = assetModel::where('name', 'LIKE', "%$query%")
                ->orWhere('code', 'LIKE', "%$query%")
                ->get();

            $assetIds = $assets->pluck('id'); // Get IDs of matching assets

            $maintenanceRecords = Maintenance::whereIn('asset_key', $assetIds)
                ->orWhere('description', 'LIKE', "%$query%")
                ->orWhere('type', 'LIKE', "%$query%")
                ->get();
        } elseif ($userType === 'dept_head') {
            // Dept Head logic: Filter assets by department and search within those
            $assets = assetModel::where('dept_ID', $deptId)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%")
                        ->orWhere('code', 'LIKE', "%$query%");
                })
                ->get();

            $assetIds = $assets->pluck('id'); // Get IDs of matching assets

            $maintenanceRecords = Maintenance::whereIn('asset_key', $assetIds)
                ->orWhere(function ($q) use ($query) {
                    $q->where('description', 'LIKE', "%$query%")
                        ->orWhere('type', 'LIKE', "%$query%");
                })
                ->get();
        }

        // Return the results to a view
        return view('search.results', compact('users', 'assets', 'maintenanceRecords', 'query'));
    }
}
