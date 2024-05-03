<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'date' => $this->faker->date(),
            'comment' => $this->faker->text(120),
            'task_id' => $this->faker->numberBetween(1, 4),
        ];
    }
}
