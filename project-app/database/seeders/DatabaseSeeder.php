<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            deptSeeder::class,
            ManufacturerSeeder::class,
            ModelSeeder::class,
            location::class,
            category::class,
            assetSeeder::class,
            MaintenanceSeeder::class,
        ]);

        // For specific records, you can use:
        // test accounts
        User::create([
            'employee_id' => 'FMS-USR001',
            'email' => 'userIT@gmail.com',
            'firstname' => 'IT',
            'lastname' => 'User',
            'middlename' => 'Test',
            'address' => 'Babag',
            'birthdate' => '2000-06-24',
            'gender' => 'male',
            'contact' => '09123456789',
            'dept_id' => 1,
            'password' => '12345678',
            'usertype' => 'user',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);

        User::create([
            'employee_id' => 'FMS-DPT_IT001',
            'email' => 'it@gmail.com',
            'firstname' => 'IT',
            'lastname' => 'DeptHead',
            'middlename' => 'Test',
            'address' => 'Babag',
            'birthdate' => '2000-06-24',
            'gender' => 'male',
            'contact' => '09123456789',
            'dept_id' => 1,
            'password' => '12345678',
            'usertype' => 'dept_head',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);

        User::create([
            'employee_id' => 'FMS-DPT_SLS001',
            'email' => 'sales@gmail.com',
            'firstname' => 'Sales',
            'lastname' => 'DeptHead',
            'middlename' => 'Test',
            'address' => 'Babag',
            'birthdate' => '2000-06-24',
            'gender' => 'male',
            'contact' => '09123456789',
            'dept_id' => 2,
            'password' => '12345678',
            'usertype' => 'dept_head',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);

        User::create([
            'employee_id' => 'FMS-DPT_FLT001',
            'email' => 'fleet@gmail.com',
            'firstname' => 'Fleet',
            'lastname' => 'DeptHead',
            'middlename' => 'Test',
            'address' => 'Babag',
            'birthdate' => '2000-06-24',
            'gender' => 'male',
            'contact' => '09123456789',
            'dept_id' => 3,
            'password' => '12345678',
            'usertype' => 'dept_head',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);

        User::create([
            'employee_id' => 'FMS-DPT_PRD001',
            'email' => 'production@gmail.com',
            'firstname' => 'Production',
            'lastname' => 'DeptHead',
            'middlename' => 'Test',
            'address' => 'Babag',
            'birthdate' => '2000-06-24',
            'gender' => 'male',
            'contact' => '09123456789',
            'dept_id' => 4,
            'password' => '12345678',
            'usertype' => 'dept_head',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);

        User::create([
            'employee_id' => 'FMS-ADMN001',
            'email' => 'admin@gmail.com',
            'firstname' => 'Admin',
            'lastname' => 'Test',
            'middlename' => 'Lorem',
            'address' => 'Babag',
            'birthdate' => '2000-06-24',
            'gender' => 'male',
            'contact' => '09123456789',
            'dept_id' => 1,
            'password' => '12345678',
            'usertype' => 'admin',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'employee_id' => 'FMS-DPT_IT002',
            'email' => 'alphapuppey@gmail.com',
            'firstname' => 'joshua loui',
            'lastname' => 'Soqueno',
            'middlename' => 'raboy',
            'address' => 'default St. Address',
            'birthdate' => Carbon::parse('2000-10-04'),
            'gender' => 'male',
            'contact' => '09123456789',
            'dept_id'=> 1,
            'password' => '12345678',
            'usertype' => 'admin',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'employee_id' => 'FMS-DPT_ALLEN',
            'email' => 'userSales@gmail.com',
            'firstname' => 'Sales',
            'lastname' => 'User',
            'middlename' => 'Test',
            'address' => 'default St. Address',
            'birthdate' => Carbon::parse('2000-10-04'),
            'gender' => 'male',
            'contact' => '09123456789',
            'dept_id'=> 2,
            'password' => '12345678',
            'usertype' => 'user',
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ]);
    }
}
