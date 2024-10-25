<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            deptSeeder::class,
            UserSeeder::class,
            ManufacturerSeeder::class,
            ModelSeeder::class,
            location::class,
            category::class,
            assetSeeder::class,
            MaintenanceSeeder::class,
        ]);
    }
}
