<?php

namespace Database\Seeders;

use App\Models\assetModel as ModelAsset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class assetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $faker;
    public function run(): void
    {
        //
        ModelAsset::factory()->create([
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'custom_fields' =>json_encode(['default1'=> 'default',
            'default2'=> 'default',
            'default3'=> 'default',
            'default4'=> 'default',
            'default5'=> 'default']),
            'manufacturer_key'=> 1,
            'maintenance_key'=> 1,
            'model_key'=> 1,
        ]
        );
    }
}
