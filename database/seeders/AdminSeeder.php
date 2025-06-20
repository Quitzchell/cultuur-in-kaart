<?php

namespace Database\Seeders;

use App\Enums\Coordinator\Role;
use App\Models\Coordinator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment() === 'local') {
            Coordinator::create([
                'name' => 'Mitchell Quitz',
                'role' => Role::Administrator->value,
                'email' => 'mitchell@soc.nl',
                'email_verified_at' => now(),
                'phone' => '06987654321',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]);
        } else {
            Coordinator::create([
                'name' => 'Example Coordinator',
                'role' => Role::Administrator->value,
                'email' => 'example@soc.nl',
                'email_verified_at' => now(),
                'phone' => '06123456789',
                'password' => Hash::make(config('services.admin.password')),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
