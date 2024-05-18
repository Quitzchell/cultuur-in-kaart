<?php

namespace Database\Seeders;

use App\Models\Coordinator;
use App\Models\Neighbourhood;
use App\Models\Project;
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
            NeighbourhoodSeeder::class
        ]);

        $this->createModels();


        foreach (Coordinator::all() as $coordinator) {
            $coordinator->neighbourhoods()->attach(Neighbourhood::all()->random(2));
            $coordinator->projects()->attach(Project::all()->random(2));
        }

    }

    private function createModels(): void
    {
        Coordinator::factory(2)->create();
        Project::factory(5)->create();
    }
}
