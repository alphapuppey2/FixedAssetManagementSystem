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
            'name' => 'Lalo',
            'email' => 'allen@gmail.com',
            'firstname' => 'Lalo',
            'lastname' => 'Dane',
            'middlename' => 'Itoy',
            'address' => 'Kanghalo',
            'birthdate' => 'Ako Birthday',
            'gender' => 'Laki AF',
            'contact' => 'ako number',
            'dept_id' => 1,
            'password' => '12345678',
            
            
        ]);
    }
}
