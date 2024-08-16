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

        $fields =array(
            'default1'=> 'default',
            'default2'=> 'default',
            'default3'=> 'default',
            'default4'=> 'default',
            'default5'=> 'default',
        )
        ;

        $obj = implode(' ', $fields);
        return [
            //
            'name'=> $this->faker->sentence(2),
            'ctg_ID'=> 1,
            'dept_ID'=> 1,
            'manufacturer_key'=> 1,
            'custom_fields' => $fields,
            'model_key'=> 1,
        ];
    }
}
