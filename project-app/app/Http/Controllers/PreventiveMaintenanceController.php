<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preventive;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PreventiveMaintenanceController extends Controller
{
    public function checkAndGenerate()
    {
        Log::info('Preventive maintenance check started.');

        // Fetch all preventive maintenance records
        $preventiveSchedules = Preventive::all();

        foreach ($preventiveSchedules as $schedule) {
            // Use Carbon to parse the created_at timestamp from the database
            $start = Carbon::parse($schedule->created_at);
            $now = Carbon::now(); // Current date/time

            // Log both times to debug the issue
            Log::info("Now: " . $now->toDateTimeString());
            Log::info("Parsed created_at for asset_key: " . $schedule->asset_key . " is: " . $start->toDateTimeString());

            // Calculate the total days between now and created_at
            if ($start->greaterThan($now)) {
                Log::info("Skipping asset_key: " . $schedule->asset_key . " because created_at is in the future.");
                continue;
            }

            $totalDays = $start->diffInDays($now);  // Ensure positive days

            Log::info("Total days since created_at: " . $totalDays);

            // Calculate occurrences based on frequency
            $expectedOccurrences = floor($totalDays / $schedule->frequency);
            Log::info("Expected occurrences: " . $expectedOccurrences);

            // Check if 'ends' is not reached or if it is set to 'never' (ends = 0)
            if ($schedule->ends == 0 || $expectedOccurrences < $schedule->ends) {
                $existingOccurrences = Maintenance::where('asset_key', $schedule->asset_key)
                                                  ->where('status', 'preventive')
                                                  ->count();
                Log::info("Existing occurrences: " . $existingOccurrences);

                if ($expectedOccurrences > $existingOccurrences) {
                    // Create the maintenance request
                    Maintenance::create([
                        'description' => 'Scheduled preventive maintenance',
                        'type' => 'maintenance',
                        'cost' => $schedule->cost,
                        'requested_at' => now(),
                        'status' => 'request',
                        'asset_key' => $schedule->asset_key,
                        'requestor' => null, // Assuming system-generated
                        'authorized_by' => null, // Assuming system-generated
                        'reason' => 'System-generated preventive maintenance',
                    ]);

                    Log::info("Maintenance request created for asset_key: " . $schedule->asset_key);
                } else {
                    Log::info("No new maintenance request needed for asset_key: " . $schedule->asset_key);
                }
            } else {
                Log::info("Maintenance 'ends' reached or no more occurrences allowed for asset_key: " . $schedule->asset_key);
            }
        }

        Log::info('Preventive maintenance check completed.');
        return "Preventive maintenance check completed.";
    }
}

