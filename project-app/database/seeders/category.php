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
            'name' => 'Mouse',
            'description'=>"peripherals",
            'dept_ID' => 1
        ]);
        CategoryModel::create([
            'name' => 'Keyboard',
            'description'=>"peripherals",
            'dept_ID' => 1
        ]);
        CategoryModel::create([
            'name' => 'Calculator',
            'description'=>"for computations",
            'dept_ID' => 2
        ]);
        CategoryModel::create([
            'name' => 'Pencil',
            'description'=>"for writing that needs erasures",
            'dept_ID' => 2
        ]);
        CategoryModel::create([
            'name' => 'Tyre',
            'description'=>"Reserve tyres",
            'dept_ID' => 3
        ]);
        CategoryModel::create([
            'name' => 'Brakes',
            'description'=>"For safety",
            'dept_ID' => 3
        ]);
        CategoryModel::create([
            'name' => 'Compressor',
            'description'=>"Increases the pressure of a gas by reducing its volume",
            'dept_ID' => 4
        ]);
        CategoryModel::create([
            'name' => 'Slicer',
            'description'=>"to slice meats, sausages, cheeses and other deli products",
            'dept_ID' => 4
        ]);
    }
}
