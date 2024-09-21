<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Predictive;
use App\Models\assetModel; // Import the Asset model

class PredictiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example: Create 20 dummy predictive maintenance records
        $assets = assetModel::all();

        foreach ($assets as $asset) {
            Predictive::create([
                'repair_count' => rand(1, 10),
                'average_cost' => rand(500, 10000),
                'recommendation' => ['maintenance', 'repair', 'dispose'][array_rand(['maintenance', 'repair', 'dispose'])],
                'asset_key' => $asset->id,
            ]);
        }
    }
}
