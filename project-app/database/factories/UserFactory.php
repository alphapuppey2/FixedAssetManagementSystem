<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Department;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'firstname' => fake()->name(),
            'lastname' => fake()->name(),
            'middlename' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Password hashing
            'remember_token' => Str::random(10),
            'address' => $this->faker->address,
            'birthdate' => $this->faker->date,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'contact' => $this->faker->phoneNumber,
            'dept_id' => Department::factory(),
            'usertype' => $this->faker->randomElement(['user', 'dept_head', 'admin']),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
