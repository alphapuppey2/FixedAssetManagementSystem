<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Preventive;
use App\Models\assetModel;

class PreventiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = assetModel::all();

        foreach ($assets as $asset) {
            Preventive::create([
                'cost' => rand(1000, 5000),
                'frequency' => rand(1, 12),
                'ends' => rand(1, 5),
                'asset_key' => $asset->id,
            ]);
        }
    }
}
