<?php

namespace Database\Factories;

use App\Models\Firm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Zaposleni>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $users = User::all();
        // $firms = Firm::all();

        // $user = collect($users)->random();
        // $firm = collect($firms)->random();
        return [
            // 'user_id' => $user->id,
            // 'firma_pib' => $firm->PIB,
            // 'AddedAt' => fake()->date(),
            // 'privileges' => fake()->randomElement(['Read', 'Write', 'Admin'])
        ];
    }
}
