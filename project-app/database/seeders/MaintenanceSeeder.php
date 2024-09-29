<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class  MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get random asset ids and user ids to use in foreign key fields
        $assetIds = DB::table('asset')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Ensure we have assets and users to use in the seeding
        if (empty($assetIds) || empty($userIds)) {
            return; // Exit if there are no assets or users
        }

        for ($i = 0; $i < 20; $i++) {
            // Determine if the maintenance is completed
            $isCompleted = $faker->boolean;

            DB::table('maintenance')->insert([
                'description' => $faker->sentence, // Generate a random sentence
                'type' => $faker->randomElement(['repair', 'maintenance', 'upgrade', 'inspection', 'replacement', 'calibration']), // Random maintenance type
                'cost' => $faker->randomFloat(2, 100, 5000), // Random cost between 100 and 5000 with 2 decimal places
                'requested_at' => $faker->dateTimeBetween('-1 month', 'now'), // Random requested date in the past month
                'authorized_at' => $faker->dateTimeBetween('now', '+1 month'), // Random authorized date in the next month
                'start_date' => $faker->dateTimeBetween('now', '+2 months'), // Random start date in the next 2 months
                'completion_date' => $isCompleted ? $faker->dateTimeBetween('+2 months', '+3 months') : null, // Completion date only if maintenance is completed
                'reason' => $faker->sentence, // Generate a random sentence for reason
                'status' => $faker->randomElement(['request', 'approved', 'denied', 'preventive', 'predictive']), // Random status
                'completed' => $isCompleted, // Set completed to true or false
                'asset_key' => $faker->randomElement($assetIds), // Random asset id from asset table
                'authorized_by' => $faker->randomElement($userIds), // Random user id from users table
                'requestor' => $faker->randomElement($userIds), // Random requestor user id from users table
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
