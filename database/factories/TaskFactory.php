<?php

namespace Database\Factories;

use App\Models\Neighbourhood;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Neighbourhood>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'emoji_unicode' => "\u" . $this->faker->emoji(),
        ];
    }
}