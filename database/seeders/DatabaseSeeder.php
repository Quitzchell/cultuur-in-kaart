<?php

namespace Database\Seeders;

use App\Models\Coordinator;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            TaskSeeder::class,
            NeighbourhoodSeeder::class,
        ]);

        if (app()->environment() !== 'production') {
            Coordinator::factory()->times(3)->create();
        }
    }
}
