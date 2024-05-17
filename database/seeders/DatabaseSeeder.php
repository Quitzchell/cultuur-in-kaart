<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Coordinator;
use App\Models\Neighbourhood;
use App\Models\Partner;
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
            TaskSeeder::class,
            NeighbourhoodSeeder::class,
            DisciplineSeeder::class,
        ]);

        if (app()->environment() !== 'production') {
            // Generate ContactPeople
            ContactPerson::factory(50)->create();

            // Generate Coordinators
            Coordinator::factory(10)->create()->each(function (Coordinator $coordinator) {
                // Attach Neighbourhoods
                $neighbourhoods = Neighbourhood::all()->random(3);
                foreach ($neighbourhoods as $neighbourhood) {
                    $coordinator->neighbourhoods()->attach($neighbourhood);
                }
            });

            // Generate Partners
            Partner::factory(30)->create()->each(function (Partner $partner) {
                // Associate Neighbourhood
                $partner->neighbourhood()->associate(Neighbourhood::all()->random());
                $partner->save();

                // Attach ContactPeople
                $partner->contactPeople()->attach(ContactPerson::all()->random(2));

                // Associate PrimaryContactPerson
                $partner->primaryContactPerson()->associate($partner->contactPeople()->inRandomOrder()->first());
                $partner->save();
            });

            // Generate Projects
            Project::factory(10)->create()->each(function (Project $project) {
                // Attach Coordinators
                $project->coordinators()->attach(Coordinator::all()->random(3));

                // Attach Primary Coordinator
                $project->coordinator()->associate($project->coordinators()->inRandomOrder()->first());
                $project->save();
            });

            // Activities
            Activity::factory(100)->create()->each(function (Activity $activity) {
                // Associate Project
                $activity->project()->associate(Project::all()->random());
                $activity->save();

                // Associate Task
                $activity->task()->associate(Task::all()->random());
                $activity->save();

                // Attach Neighbourhoods
                $activity->neighbourhood()->associate(Neighbourhood::all()->random());
                $activity->save();

                // Attach Coordinators
                $coordinators = $activity->project->coordinators->random(3);
                $activity->coordinators()->attach($coordinators);

                // Attach Partners
                $activity->partners()->attach(Partner::all()->random(2));
            });
        }
    }
}
