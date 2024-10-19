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
        //
        for ($i = 1; $i <= 50; $i++) {
            DB::table('asset')->insert([
                'name' => 'Asset ' . $i,
                'asst_img' => null, // No image for now
                'qr_img' => null, // Can be null as per your request
                'code' => Str::random(10), // Random unique code
                'purchase_date' => Carbon::now()->subDays(rand(365, 1825)), // Random date in the past 1 to 5 years
                'purchase_cost' => rand(5000, 50000), // Random cost between 5000 and 50000
                'depreciation' => rand(500, 5000), // Random depreciation
                'salvage_value' => rand(500, 5000), // Random salvage value
                'usage_lifespan' => rand(5, 20), // Random usage lifespan (in years)
                'status' => $this->getRandomAssetStatus(), // Only relevant statuses for predictive maintenance
                'custom_fields' => null, // Can be null

                'ctg_ID' => rand(1, 4), // Random category between 1 and 4
                'dept_ID' => rand(1, 4), // Random department between 1 and 4
                'manufacturer_key' => rand(1, 3), // Random manufacturer between 1 and 3
                'model_key' => rand(1, 4), // Random model between 1 and 4
                'loc_key' => rand(1, 4), // Random location between 1 and 4

                'created_at' => Carbon::now()->subDays(rand(365, 1825)), // Random created date in past 1-5 years
                'updated_at' => Carbon::now(), // Current timestamp for updated_at
            ]);
        }

        ModelAsset::create([
            'code' =>'TST-0001',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0002',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0003',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0004',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0005',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0006',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0007',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0008',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0009',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0010',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0011',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0012',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0013',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0014',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0015',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0016',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0017',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0018',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0019',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
        ModelAsset::create([
            'code' =>'TST-0020',
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'name' => "nameless",
            'custom_fields' =>json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key' => 1,
            'model_key'=> 1,
        ]
        );
    }

        /**
     * Get a random asset status excluding 'disposed'.
     */
    private function getRandomAssetStatus()
    {
        // Exclude 'disposed' since predictive maintenance doesn't apply to disposed assets
        $statuses = ['active', 'deployed', 'under_maintenance', 'disposed'];
        return $statuses[array_rand($statuses)];
    }
}
