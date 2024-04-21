<?php

namespace Database\Factories;

use App\Models\ContactPerson;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
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
