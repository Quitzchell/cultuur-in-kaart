<?php

namespace Database\Factories;

use App\Models\Neighbourhood;
use Illuminate\Database\Eloquent\Factories\Factory;

class NeighbourhoodFactory extends Factory
{
    /**
     * @extends Factory<Neighbourhood>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city(),
        ];
    }
}
