<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 1, 300),
            'duration' => $this->faker->randomNumber(2),
            'duration_unit' => $this->faker->randomElement(['day', 'week', 'month', 'year']),
            'description' => $this->faker->sentence,
            'gym_id' => \App\Models\Gym::factory(),
        ];
    }
}
