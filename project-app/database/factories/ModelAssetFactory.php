<?php

namespace Database\Factories;

use App\Models\ModelAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModelAsset>
 */
class ModelAssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = ModelAsset::class;
    public function definition(): array
    {
        return [
            //
            "name"=> $this->faker->sentence(2),
            "description" => $this->faker->sentence(10),
        ];
    }
}
