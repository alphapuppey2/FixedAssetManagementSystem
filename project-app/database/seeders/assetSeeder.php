<?php

namespace Database\Seeders;

use App\Models\assetModel as ModelAsset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class assetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $faker;
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            // Randomly assign IT or Sales department
            $isITDepartment = rand(0, 1) === 1;
            $departmentId = $isITDepartment ? 1 : 2;

            // Set ranges based on department
            $categoryId = $isITDepartment ? rand(1, 2) : rand(3, 4);
            $manufacturerId = $isITDepartment ? rand(1, 2) : rand(3, 4);
            $modelId = $isITDepartment ? rand(1, 2) : rand(3, 4);
            $locationId = $isITDepartment ? rand(1, 2) : rand(3, 4);

            DB::table('asset')->insert([
                'name' => 'Asset ' . $i,
                'asst_img' => null, // No image for now
                'qr_img' => null, // Can be null
                'code' => Str::random(10), // Random unique code
                'purchase_date' => Carbon::now()->subDays(rand(365, 1825)), // Random date in the past 1 to 5 years
                'purchase_cost' => rand(5000, 50000), // Random cost between 5000 and 50000
                'depreciation' => rand(500, 5000), // Random depreciation
                'salvage_value' => rand(500, 5000), // Random salvage value
                'usage_lifespan' => rand(5, 20), // Random usage lifespan (in years)
                'status' => $this->getRandomAssetStatus(), // Only relevant statuses for predictive maintenance
                'custom_fields' => null, // Can be null

                'ctg_ID' => $categoryId,
                'dept_ID' => $departmentId,
                'manufacturer_key' => $manufacturerId,
                'model_key' => $modelId,
                'loc_key' => $locationId,

                'created_at' => Carbon::now()->subDays(rand(365, 1825)), // Random created date in past 1-5 years
                'updated_at' => Carbon::now(), // Current timestamp for updated_at
            ]);
        }
    }

        /**
     * Get a random asset status excluding 'disposed'.
     */
    private function getRandomAssetStatus()
    {
        // Exclude 'disposed' since predictive maintenance doesn't apply to disposed assets
        $statuses = ['active', 'deployed', 'under_maintenance'];
        return $statuses[array_rand($statuses)];
    }
}
