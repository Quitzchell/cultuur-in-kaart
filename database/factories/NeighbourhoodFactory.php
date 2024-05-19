<?php

namespace Database\Factories;

use App\Models\Neighbourhood;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Neighbourhood>
 */
class NeighbourhoodFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
        ];
    }
}
