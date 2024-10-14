<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AssetSeeder_Predictive extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 assets for predictive maintenance testing
        for ($i = 1; $i <= 50; $i++) {
            DB::table('asset')->insert([
                'name' => 'Asset ' . $i,
                'image' => null, // No image for now
                'qr' => null, // Can be null as per your request
                'code' => Str::random(10), // Random unique code
                'purchase_date' => Carbon::now()->subDays(rand(365, 1825)), // Random date in the past 1 to 5 years
                'cost' => rand(5000, 50000), // Random cost between 5000 and 50000
                'depreciation' => rand(500, 5000), // Random depreciation
                'salvageVal' => rand(500, 5000), // Random salvage value
                'usage_Lifespan' => rand(5, 20), // Random usage lifespan (in years)
                'status' => $this->getRandomAssetStatus(), // Only relevant statuses for predictive maintenance
                'custom_fields' => null, // Can be null

                'ctg_ID' => rand(1, 4), // Random category between 1 and 4
                'dept_ID' => rand(1, 4), // Random department between 1 and 4
                'manufacturer_key' => rand(1, 3), // Random manufacturer between 1 and 3
                'model_key' => rand(1, 4), // Random model between 1 and 4
                'loc_key' => rand(1, 4), // Random location between 1 and 4

                'created_at' => Carbon::now()->subDays(rand(365, 1825)), // Random created date in past 1-5 years
                'updated_at' => Carbon::now(), // Current timestamp for updated_at
            ]);
        }
    }

    /**
     * Get a random asset status excluding 'disposed'.
     */
    private function getRandomAssetStatus()
    {
        // Exclude 'disposed' since predictive maintenance doesn't apply to disposed assets
        $statuses = ['active', 'deployed', 'need_repair', 'under_maintenance'];
        return $statuses[array_rand($statuses)];
    }
}
