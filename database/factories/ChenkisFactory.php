<?php

namespace Database\Factories;

use App;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chenkis>
 */
class ChenkisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => App\Models\Member::factory(),
            'gym_id' => App\Models\Gym::factory(),
            'created_by' => $this->faker->name(),
        ];
    }
}
