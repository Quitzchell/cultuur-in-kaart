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
            'name' => fake()->firstName() . fake()->lastName(),
            'role' => Role::Werknemer->value,
            'email' => fake()->unique()->userName() . '@soc.nl',
            'email_verified_at' => now(),
            'phone' => fake()->e164PhoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'workdays' => [Workday::Monday->value, Workday::Tuesday->value, Workday::Wednesday->value]
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
