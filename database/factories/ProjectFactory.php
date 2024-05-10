<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeThisMonth()->format('Y-m-d');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 month')->format('Y-m-d');

        return [
            'name' => $this->faker->name(),
            'project_number' => $this->faker->randomNumber(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'budget_spend' => $this->faker->numberBetween(000, 1000000),
        ];
    }
}
