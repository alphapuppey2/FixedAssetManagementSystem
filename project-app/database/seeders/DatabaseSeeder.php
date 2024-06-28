<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
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
            'firstname' => 'joshua loui',
            'lastname' => 'Soqueno',
            'middlename' => 'raboy',
            'contact' => '09123456789',
            'address' => 'default St. Address',
            'gender' => 'male',
            'dept_id'=> 1,
            'birthdate' => Carbon::parse('2000-10-04'),
            'email' => 'alphapuppey@gmail.com',
        ]);
    }
}
