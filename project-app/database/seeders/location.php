<?php

namespace Database\Seeders;

use App\Models\locationModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class location extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        locationModel::create([
            'name' => 'Office Bldg- Cebu',
            'description' => 'Businnes Branches'
        ]);
        locationModel::create([
            'name' => 'Plantation Factory - Consolation',
            'description' => 'Businnes Branches'
        ]);
        locationModel::create([
            'name' => 'Plantation Factory - Carcar',
            'description' => 'Businnes Branches'
        ]);
        locationModel::create([
            'name' => 'Office Bldg- Danao',
            'description' => 'Businnes Branches'
        ]);
    }
}
