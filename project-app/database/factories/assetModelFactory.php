<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\assetModel>
 */
class assetModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            //
            'name'=> $this->faker->sentence(2),
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'custom_field' => json_encode([
                'default1'=> 'default',
                'default2'=> 'default',
                'default3'=> 'default',
                'default4'=> 'default',
                'default5'=> 'default'
            ]),
            'manufacturer_key'=> 1,
            'loc_key'=> 1,
            'model_key'=> 1,
        ];
    }
}
