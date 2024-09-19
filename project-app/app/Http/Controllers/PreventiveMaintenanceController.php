<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preventive;
use App\Models\Maintenance;
use Carbon\Carbon;


class PreventiveMaintenanceController extends Controller
{
    public function checkAndGenerate()
    {
        // Fetch all preventive maintenance records
        $preventiveSchedules = Preventive::all();

        foreach ($preventiveSchedules as $schedule) {
            // Calculate the number of occurrences that should have happened
            $now = Carbon::now();
            $start = Carbon::parse($schedule->created_at);
            $totalDays = $now->diffInDays($start); // Total days since the preventive maintenance was created

            // Calculate occurrences based on frequency
            $expectedOccurrences = floor($totalDays / $schedule->frequency);

            // Check if 'ends' is not reached or if it is set to 'never' (ends = 0)
            if ($schedule->ends == 0 || $expectedOccurrences < $schedule->ends) {
                // If the expectedOccurrences is greater than the number of maintenance requests already made
                $existingOccurrences = Maintenance::where('asset_key', $schedule->asset_key)
                                                  ->where('status', 'preventive')
                                                  ->count();

                if ($expectedOccurrences > $existingOccurrences) {
                    // Generate new maintenance request
                    Maintenance::create([
                        'description' => 'Scheduled preventive maintenance',
                        'type' => 'maintenance',
                        'cost' => $schedule->cost,
                        'requested_at' => now(),
                        'status' => 'preventive',
                        'asset_key' => $schedule->asset_key,
                        'requestor' => 0, // System-generated
                        'reason' => 'System-generated preventive maintenance',
                    ]);
                }
            }
        }

        return "Preventive maintenance check completed.";
    }
}
