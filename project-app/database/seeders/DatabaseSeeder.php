<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'joshua loui Soqueno',
            'firstname' => 'joshua loui',
            'lastname' => 'Soqueno',
            'middlename' => 'raboy',
            'contact' => '09123456789',
            'birthdate' => '09123456789',
            'email' => 'test@example.com',
        ]);
    }
}
