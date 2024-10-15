<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve all assets from the asset table
        $assets = DB::table('asset')->get();

        foreach ($assets as $asset) {
            // Create 5-10 maintenance records for each asset
            for ($i = 0; $i < 3; $i++) {
                // Generate dates logically: requested first, then start, then completion
                $requestedAt = Carbon::now()->subDays(rand(30, 180)); // Random date in past 6 months
                $startDate = Carbon::now()->subDays(rand(10, 20)); // Random start date after request
                $completionDate = rand(0, 1) ? Carbon::now()->subDays(rand(1, 10)) : null; // Some may not be completed

                // Decide if the maintenance is completed
                $isCompleted = rand(0,1);

                // Set the status based on completion
                // $status = $isCompleted ? 'completed' : $this->getRandomStatus();

                // Insert into maintenance table
                DB::table('maintenance')->insert([
                    'description' => 'Maintenance for ' . $asset->name, // Use asset name in description
                    'type' => $this->getRandomMaintenanceType(),
                    'cost' => rand(1000, 10000), // Random cost for the maintenance
                    'requested_at' => $requestedAt,
                    'authorized_at' => rand(0, 1) ? Carbon::now()->subDays(rand(5, 15)) : null, // Some may be authorized
                    'start_date' => $startDate,
                    'completion_date' => $completionDate,
                    'is_completed' => $isCompleted, // 1 if completed, 0 otherwise
                    'reason' => $isCompleted ? 'Routine check' : null, // Reason only if completed
                    'status' => $this->getRandomStatus(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'asset_key' => $asset->id, // Link to the asset's ID
                    'authorized_by' => null, // Set to null
                    'requestor' => null, // Set to null
                ]);
            }
        }
    }

    /**
     * Get a random maintenance status excluding 'completed' for incomplete entries.
     */
    private function getRandomStatus()
    {
        $statuses = ['request', 'approved', 'denied', 'cancelled'];
        return $statuses[array_rand($statuses)];
    }

    /**
     * Get a random maintenance type.
     */
    private function getRandomMaintenanceType()
    {
        $types = ['repair','maintenance','upgrade','inspection','replacement','calibration'];
        return $types[array_rand($types)];
    }
}
