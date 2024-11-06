<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        // Retrieve all assets in IT and Sales departments
        $assets = DB::table('asset')
            ->whereIn('dept_ID', [1, 2])
            ->get();

        foreach ($assets as $asset) {
            $initialMaintenanceCount = rand(5, 10); // Start with 5-10 maintenance records
            $baseCost = rand(500, 2000);
            $lastCompletionDate = Carbon::now()->subDays(rand(365, 1095)); // 1-3 years ago

            for ($i = 0; $i < $initialMaintenanceCount; $i++) {
                // Set time intervals progressively shorter, simulating degradation
                $daysSinceLastMaintenance = rand(60, 180) - ($i * 10);
                $requestedAt = $lastCompletionDate->copy()->addDays(rand(1, 10));
                $startDate = $requestedAt->copy()->addDays(rand(1, 5));

                // Ensure some maintenance records are explicitly labeled as "maintenance"
                $isCompleted = $i < ($initialMaintenanceCount * 0.7) ? 1 : rand(0, 1);
                $completionDate = $isCompleted ? $startDate->copy()->addDays(rand(1, 5)) : null;

                // Randomize type but ensure at least some are "maintenance"
                $maintenanceType = $i < 2 ? 'maintenance' : $this->getRealisticMaintenanceType($i); // At least the first 2 are maintenance

                // Alternate status between 'approved' and random for incomplete records
                $status = $isCompleted ? 'approved' : $this->getRandomStatus();

                DB::table('maintenance')->insert([
                    'description' => 'Maintenance for ' . $asset->name,
                    'type' => $maintenanceType,
                    'cost' => $baseCost + ($i * rand(100, 500)),
                    'requested_at' => $requestedAt,
                    'authorized_at' => rand(0, 1) ? $requestedAt->copy()->addDays(rand(1, 3)) : null,
                    'start_date' => $isCompleted ? $startDate : null,
                    'completion_date' => $isCompleted ? $completionDate : null,
                    'is_completed' => $isCompleted,
                    'reason' => $isCompleted ? 'Routine or wear and tear' : null,
                    'status' => $status,
                    'created_at' => $requestedAt,
                    'updated_at' => $completionDate ?? Carbon::now(),
                    'asset_key' => $asset->id,
                    'authorized_by' => null,
                    'requestor' => null,
                ]);

                // Update the lastCompletionDate for the next iteration
                $lastCompletionDate = $completionDate ? $completionDate->copy()->addDays($daysSinceLastMaintenance) : $lastCompletionDate;
            }
        }
    }

    private function getRealisticMaintenanceType($index)
    {
        // Ensure that maintenance types are realistically distributed
        $types = ['inspection', 'repair', 'maintenance', 'upgrade', 'replacement', 'calibration'];
        return $types[array_rand($types)];
    }

    private function getRandomStatus()
    {
        $statuses = ['request', 'approved', 'denied', 'cancelled'];
        return $statuses[array_rand($statuses)];
    }
}
