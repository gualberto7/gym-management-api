<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => $startDate = $this->faker->dateTimeBetween('now', '+1 day'),
            'end_date' => $this->faker->dateTimeBetween($startDate, '+1 year'),
            'membership_id' => \App\Models\Membership::factory(),
            'member_id' => \App\Models\Member::factory(),
            'gym_id' => \App\Models\Gym::factory(),
        ];
    }
}
