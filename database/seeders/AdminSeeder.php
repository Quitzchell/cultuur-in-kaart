<?php

namespace Database\Seeders;

use App\Enums\Coordinator\Role;
use App\Models\Coordinator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment() !== 'production') {
            $admin = [
                'name' => 'Mitchell Quitz',
                'role' => Role::Administrator->value,
                'email' => 'mitchell@soc.nl',
                'email_verified_at' => now(),
                'phone' => '0628301804',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ];
        } else {
            $admin = [
                'name' => 'Melanie Leeflang',
                'role' => Role::Administrator->value,
                'email' => 'melanie@soc.nl',
                'email_verified_at' => now(),
                'phone' => '0638045527',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ];
        }

        Coordinator::create($admin);
    }
}
