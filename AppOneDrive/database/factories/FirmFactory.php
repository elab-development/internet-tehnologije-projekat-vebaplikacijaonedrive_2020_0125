<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Firma>
 */
class FirmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'PIB' => fake()->numberBetween(100000000, 999999999),
            'name' => fake()->company(),
            'address'=>fake()->streetAddress(),
            'createdAt' => fake()->dateTime(),
            'user_id' => User::factory(),
        ];
    }}
