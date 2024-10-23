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
    // public function showPreventive(Request $request)
    // {
    //     // Get the logged-in user's department ID
    //     $userDeptId = Auth::user()->dept_id;

    //     // Get the number of rows to display from the request, default to 10
    //     $perPage = $request->input('rows_per_page', 10);

    //     // Get the search query from the request
    //     $searchQuery = $request->input('query', '');

    //     // Sorting logic
    //     $sortBy = $request->input('sort_by', 'asset.name'); // Default sort by asset name
    //     $sortOrder = $request->input('sort_order', 'asc'); // Default sort order is ascending

    //     // Fetch preventive maintenance records with asset details
    //     $preventives = Preventive::whereHas('asset', function ($query) use ($userDeptId, $searchQuery) {
    //         $query->where('dept_ID', $userDeptId)
    //             ->where(function($q) use ($searchQuery) {
    //                 $q->where('code', 'like', "%{$searchQuery}%")
    //                     ->orWhere('name', 'like', "%{$searchQuery}%");
    //             });
    //     })
    //     ->join('asset', 'preventive.asset_key', '=', 'asset.id') // Join the asset table
    //     ->orderBy($sortBy, $sortOrder) // Sort by asset columns
    //     ->select('preventive.*') // Select preventive columns
    //     ->paginate($perPage);

    //     foreach ($preventives as $preventive) {
    //         // Use updated_at or the last maintenance date
    //         $lastMaintenance = Carbon::parse($preventive->updated_at);

    //         // Calculate the next maintenance date by adding the frequency in days

    //         // $nextMaintenanceDate = $lastMaintenance->addDays($preventive->frequency); //actual
    //         $nextMaintenanceDate = $lastMaintenance->addSeconds(15); // 10 seconds for testing


    //         // Pass the next maintenance date as a timestamp (to be used in the frontend for real-time countdown)
    //         // $preventive->next_maintenance_timestamp = $nextMaintenanceDate->timestamp;
    //         $preventive->next_maintenance_timestamp = $nextMaintenanceDate ? $nextMaintenanceDate->timestamp : null;

    //         Log::info('Next Maintenance Timestamp:', [
    //             'timestamp' => $nextMaintenanceDate->timestamp,
    //             'asset_key' => $preventive->asset_key
    //         ]);
    //     }

    //     return view('dept_head.maintenance_sched', [
    //         'tab' => 'preventive',
    //         'records' => $preventives,
    //         'perPage' => $perPage,
    //         'searchQuery' => $searchQuery,
    //         'sortBy' => $sortBy,
    //         'sortOrder' => $sortOrder, // Pass the current sorting info to the view
    //     ]);
    // }

    public function showPreventive(Request $request)
    {
        // Determine the user's role and department ID
        $userRole = Auth::user()->role;
        $userDeptId = Auth::user()->dept_id;

        // Rows per page
        $perPage = $request->input('rows_per_page', 10);

        // Search query
        $searchQuery = $request->input('query', '');

        // Sorting
        // $sortBy = $request->input('sort_by', 'asset.name');
        // $sortOrder = $request->input('sort_order', 'asc');

        $sortBy = $request->input('sort_by', 'preventive.created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Fetch preventive records based on user role
        $preventives = Preventive::whereHas('asset', function ($query) use ($userRole, $userDeptId, $searchQuery) {
            if ($userRole === 'dept_head') {
                $query->where('dept_ID', $userDeptId);
            }
            $query->where(function ($q) use ($searchQuery) {
                $q->where('code', 'like', "%{$searchQuery}%")
                    ->orWhere('name', 'like', "%{$searchQuery}%");
            });
        })
            ->join('asset', 'preventive.asset_key', '=', 'asset.id')
            ->orderBy($sortBy, $sortOrder)
            ->select('preventive.*')
            ->paginate($perPage);

        $this->calculateNextMaintenance($preventives);

        $view = $userRole === 'admin' ? 'admin.maintenance_sched' : 'dept_head.maintenance_sched';
        return view($view, [
            'tab' => 'preventive',
            'records' => $preventives,
            'perPage' => $perPage,
            'searchQuery' => $searchQuery,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    public function calculateNextMaintenance($preventives)
    {
        foreach ($preventives as $preventive) {
            // Use updated_at or the last maintenance date
            $lastMaintenance = Carbon::parse($preventive->updated_at);

            // $nextMaintenanceDate = $lastMaintenance->addSeconds(15); // , mu reset ang time (countdown) if kani
            $nextMaintenanceDate = $lastMaintenance->addDays($preventive->frequency); //actual

            // Pass the next maintenance date as a timestamp for frontend countdown
            $preventive->next_maintenance_timestamp = $nextMaintenanceDate->timestamp ?? null;

            // Log the next maintenance timestamp for debugging
            Log::info('Next Maintenance Timestamp:', [
                'timestamp' => $nextMaintenanceDate->timestamp,
                'asset_key' => $preventive->asset_key
            ]);
        }
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
        $sortBy = $request->input('sort_by', 'predictive.created_at'); // Default sort by asset name
        $sortOrder = $request->input('sort_order', 'desc'); // Default sort order is ascending

        // Adjust sorting logic based on whether the sorting column belongs to asset or category
        $predictives = Predictive::whereHas('asset', function ($query) use ($userDeptId, $searchQuery) {
            $query->where('dept_ID', $userDeptId)
                ->where(function ($q) use ($searchQuery) {
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
