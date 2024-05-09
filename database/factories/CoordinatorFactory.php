<?php

namespace Database\Factories;

use App\Enums\Coordinator\Role;
use App\Enums\Workday\Workday;
use App\Models\Coordinator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coordinator>
 */
class CoordinatorFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'role' => Role::Employee->value,
            'email' => $this->faker->unique()->userName() . '@soc.nl',
            'email_verified_at' => now(),
            'phone' => $this->faker->e164PhoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'workdays' => Workday::labelsToCollection()->random(2),
        ];
    }

    public
    function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
