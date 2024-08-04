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
        ModelAsset::factory()->count(3)->create();
    }
}
