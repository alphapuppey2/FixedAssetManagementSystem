<?php

namespace Database\Seeders;

use App\Models\category as ModelsCategory;
use Database\Factories\categoryFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class category extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelsCategory::factory()->count(3)->create();
    }
}
