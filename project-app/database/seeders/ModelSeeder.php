<?php

namespace Database\Seeders;

use App\Models\ModelAsset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelAsset::create([
            'name' => 'RK100',
            'description' => 'keyboard',
            'dept_ID' => 1
        ]);
        ModelAsset::create([
            'name' => 'Superlight',
            'description' => 'mouse',
            'dept_ID' => 1
        ]);
        ModelAsset::create([
            'name' => 'FX360CW',
            'description' => 'calculator',
            'dept_ID' => 2
        ]);
        ModelAsset::create([
            'name' => 'Mongol 1',
            'description' => 'pencil',
            'dept_ID' => 2
        ]);
        ModelAsset::create([
            'name' => 'Pirelli',
            'description' => 'tyres',
            'dept_ID' => 3
        ]);
        ModelAsset::create([
            'name' => 'Brembo',
            'description' => 'brakes',
            'dept_ID' => 3
        ]);
        ModelAsset::create([
            'name' => 'Slicer A',
            'description' => 'For slicing meat',
            'dept_ID' => 4
        ]);
        ModelAsset::create([
            'name' => 'Compressor X',
            'description' => 'Increases the pressure',
            'dept_ID' => 4
        ]);
    }
}
