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
            'name' => 'model 1',
            'description' => 'NO CHANCES',
            'dept_ID' => 1
        ]);
        ModelAsset::create([
            'name' => 'model 2',
            'description' => 'NO CHANCES',
            'dept_ID' => 1
        ]);
        ModelAsset::create([
            'name' => 'model 3',
            'description' => 'NO CHANCES',
            'dept_ID' => 1
        ]);
        ModelAsset::create([
            'name' => 'model 4',
            'description' => 'NO CHANCES',
            'dept_ID' => 1
        ]);
    }
}
