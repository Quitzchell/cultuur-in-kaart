<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Coordinator;
use App\Models\Discipline;
use App\Models\Neighbourhood;
use App\Models\Project;
use App\Models\Task;
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
            NeighbourhoodSeeder::class,
            TaskSeeder::class,
            DisciplineSeeder::class
        ]);

        $this->createModels();

        foreach (Coordinator::all() as $coordinator) {
            $coordinator->neighbourhoods()->attach(Neighbourhood::all()->random(2));
        }

        foreach (Project::all() as $project) {
            $project->coordinator()->associate($project->coordinators()->first());
            $project->coordinators()->attach(Coordinator::all()->random(2));
            $project->save();
        }

        foreach (Activity::all() as $activity) {
            $activity->task()->associate(Task::all()->random());
            $activity->project()->associate(Project::all()->random());
            $activity->neighbourhood()->associate(Neighbourhood::all()->random());
            $activity->discipline()->associate(Discipline::all()->random());
            $activity->save();
            $activity->coordinators()->attach($activity->project->coordinators()->first());
        }

    }

    private function createModels(): void
    {
        Coordinator::factory(2)->create();
        Project::factory(25)->create();
        Activity::factory(500)->create();
    }
}
