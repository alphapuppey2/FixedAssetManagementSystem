<?php

namespace Database\Seeders;

use App\Models\department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class deptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('department')->insert([
            [
                'id' => 1,
                'name' => 'IT',
                'description' => 'Manages and supports an organization’s technology infrastructure and systems.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Sales',
                'description' => 'Drives revenue by promoting and selling products or services to customers.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Fleet',
                'description' => 'Manages and maintains vehicles to ensure efficient and cost-effective transportation.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Production',
                'description' => 'Responsible for manufacturing goods and ensuring quality and efficiency in the production process',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
