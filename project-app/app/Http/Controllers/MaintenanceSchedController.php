<?php

namespace App\Http\Controllers;

use App\Models\Predictive;
use App\Models\Preventive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MaintenanceSchedController extends Controller
{
    public function showPreventive(Request $request)
    {
        // Get the logged-in user's department ID
        $userDeptId = Auth::user()->dept_id;

        // Get the number of rows to display from the request, default to 10
        $perPage = $request->input('rows_per_page', 10);

        // Get the search query from the request
        $searchQuery = $request->input('query', '');

        // Sorting logic
        $sortBy = $request->input('sort_by', 'asset.name'); // Default sort by asset name
        $sortOrder = $request->input('sort_order', 'asc'); // Default sort order is ascending

        // Fetch preventive maintenance records with asset details
        $preventives = Preventive::whereHas('asset', function ($query) use ($userDeptId, $searchQuery) {
            $query->where('dept_ID', $userDeptId)
                ->where(function($q) use ($searchQuery) {
                    $q->where('code', 'like', "%{$searchQuery}%")
                        ->orWhere('name', 'like', "%{$searchQuery}%");
                });
        })
        ->join('asset', 'preventive.asset_key', '=', 'asset.id') // Join the asset table
        ->orderBy($sortBy, $sortOrder) // Sort by asset columns
        ->select('preventive.*') // Select preventive columns
        ->paginate($perPage);

        foreach ($preventives as $preventive) {
            // Use updated_at or the last maintenance date
            $lastMaintenance = Carbon::parse($preventive->updated_at);

            // Calculate the next maintenance date by adding the frequency in days
            // $nextMaintenanceDate = $lastMaintenance->addDays($preventive->frequency); //acttual //for testing
            // For testing, use seconds instead of days or minutes
            $nextMaintenanceDate = $lastMaintenance->addSeconds(10); // 10 seconds for testing


            // Pass the next maintenance date as a timestamp (to be used in the frontend for real-time countdown)
            // $preventive->next_maintenance_timestamp = $nextMaintenanceDate->timestamp;
            $preventive->next_maintenance_timestamp = $nextMaintenanceDate ? $nextMaintenanceDate->timestamp : null;

            Log::info('Next Maintenance Timestamp:', [
                'timestamp' => $nextMaintenanceDate->timestamp,
                'asset_key' => $preventive->asset_key
            ]);
        }

        return view('dept_head.maintenance_sched', [
            'tab' => 'preventive',
            'records' => $preventives,
            'perPage' => $perPage,
            'searchQuery' => $searchQuery,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder, // Pass the current sorting info to the view
        ]);
    }

    public function showPredictive(Request $request)
    {
        // Get the logged-in user's department ID
        $userDeptId = Auth::user()->dept_id;

        // Get the number of rows to display from the request, default to 10
        $perPage = $request->input('rows_per_page', 10);

        // Get the search query from the request
        $searchQuery = $request->input('query', '');

        // Sorting logic
        $sortBy = $request->input('sort_by', 'asset.name'); // Default sort by asset name
        $sortOrder = $request->input('sort_order', 'asc'); // Default sort order is ascending

        // Adjust sorting logic based on whether the sorting column belongs to asset or category
        $predictives = Predictive::whereHas('asset', function ($query) use ($userDeptId, $searchQuery) {
            $query->where('dept_ID', $userDeptId)
                  ->where(function($q) use ($searchQuery) {
                      $q->where('code', 'like', "%{$searchQuery}%")
                        ->orWhere('name', 'like', "%{$searchQuery}%");
                  });
        })
        ->join('asset', 'predictive.asset_key', '=', 'asset.id')
        ->join('category', 'asset.ctg_ID', '=', 'category.id')
        ->orderBy($sortBy, $sortOrder) // Sort by asset or predictive columns
        ->select('predictive.*')
        ->paginate($perPage);

        return view('dept_head.maintenance_sched', [
            'tab' => 'predictive',
            'records' => $predictives,
            'perPage' => $perPage,
            'searchQuery' => $searchQuery,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder, // Pass the current sorting info to the view
        ]);
    }
}
