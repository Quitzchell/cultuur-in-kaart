<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
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
        $startDate = $this->faker->dateTimeThisMonth();
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 month');

        return [
            'name' => $this->faker->name(),
            'project_number' => $this->faker->randomNumber(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'budget_spend' => $this->faker->numberBetween(000, 1000000),
        ];
    }
}
