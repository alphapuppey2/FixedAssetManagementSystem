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
        $userRole = Auth::user()->usertype;
        $userDeptId = Auth::user()->dept_id;
        $perPage = $request->input('rows_per_page', 10);
        $searchQuery = $request->input('query', '');
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

        // Calculate the remaining countdown time for each preventive record
        foreach ($preventives as $preventive) {
            $remainingTime = $preventive->next_maintenance_timestamp - now()->timestamp;
            $preventive->remaining_time = max($remainingTime, 0); // Prevent negative countdowns
        }

        $view = $userRole === 'admin' ? 'admin.maintenanceSched' : 'dept_head.maintenance_sched';
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
            //do not delete or remove this
            // Use the saved timestamp if it exists and is valid
            // if ($preventive->next_maintenance_timestamp && $preventive->next_maintenance_timestamp > now()->timestamp) {
            //     // Skip updating if timestamp is still valid to avoid unnecessary reset
            //     Log::info('Using existing Next Maintenance Timestamp:', [
            //         'timestamp' => $preventive->next_maintenance_timestamp,
            //         'asset_key' => $preventive->asset_key
            //     ]);
            //     continue;
            // }

            // Otherwise, calculate the next maintenance date based on the frequency
            // Use updated_at or the last maintenance date
            $lastMaintenance = Carbon::parse($preventive->updated_at);

             // Scale 1 day as 20 seconds for testing
             //first countdown, initial countdown ni
            // $nextMaintenanceDate = $lastMaintenance->addSeconds($preventive->frequency * 20); //test

            $nextMaintenanceDate = $lastMaintenance->addDays($preventive->frequency); //actual

            // Pass the next maintenance date as a timestamp for frontend countdown
            $preventive->next_maintenance_timestamp = $nextMaintenanceDate->timestamp ?? null;
            $preventive->save(); // Save the updated preventive

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
        $userRole = Auth::user()->usertype;

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


        $view = ($userRole === 'admin') ? "admin.maintenanceSched" : 'dept_head.maintenance_sched';
        return view($view, [
            'tab' => 'predictive',
            'records' => $predictives,
            'perPage' => $perPage,
            'searchQuery' => $searchQuery,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder, // Pass the current sorting info to the view
        ]);
    }
}
