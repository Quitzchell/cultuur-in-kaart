<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Coordinator;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\EmployeeFactory;
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
        ]);

        if (app()->environment() !== 'production') {
            Coordinator::factory()->times(3)->create();
        }
    }
}
