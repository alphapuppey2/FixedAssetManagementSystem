<?php

namespace Database\Seeders;

use App\Models\category as CategoryModel;
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
        CategoryModel::create([
            'name' => 'Category 1',
            'dept_ID' => 1
        ]);
        CategoryModel::create([
            'name' => 'Category 2',
            'dept_ID' => 1
        ]);
        CategoryModel::create([
            'name' => 'Category 1',
            'dept_ID' => 2
        ]);
        CategoryModel::create([
            'name' => 'Category 2',
            'dept_ID' => 2
        ]);
    }
}
