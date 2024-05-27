<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeThisMonth()->format('Y-m-d');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 month')->format('Y-m-d');

        return [
            'name' => $this->faker->sentence(3),
            'project_number' => $this->faker->randomNumber(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'budget_spend' => $this->faker->numberBetween(0, 1000000),
        ];
    }
}
