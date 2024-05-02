<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Coordinator;
use App\Models\Discipline;
use App\Models\Neighbourhood;
use App\Models\Partner;
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
            TaskSeeder::class,
            NeighbourhoodSeeder::class,
            DisciplineSeeder::class,
        ]);

        if (app()->environment() !== 'production') {
            Coordinator::factory(3)->create()->each(function (Coordinator $coordinator) {
                $neighbourhoods = Neighbourhood::all()->random(3);
                foreach ($neighbourhoods as $neighbourhood) {
                    $coordinator->neighbourhoods()->attach($neighbourhood);
                }
            });

            Project::factory(10)->create()->each(function (Project $project) {
                $contactPeople = ContactPerson::factory(5)->create();
                $project->activities()->saveMany(
                    Activity::factory(5)->make()->each(function (Activity $activity) use ($contactPeople) {
                        $contactPerson = $contactPeople->random();
                        $activity->contactPerson()->associate($contactPerson);
                        $activity->save();

                        $neighbourhood = Neighbourhood::all()->random(3);
                        $activity->neighbourhoods()->attach($neighbourhood);

                        $disciplines = Discipline::all()->random(3);
                        $activity->disciplines()->attach($disciplines);
                    })
                );
            });

            $contactPeople = ContactPerson::all();
            Partner::factory(10)->recycle($contactPeople)->create()->each(function (Partner $partner) use ($contactPeople) {
                $contactPeople = $contactPeople->random(3);
                foreach ($contactPeople as $contactPerson) {
                    $partner->contactPeople()->attach($contactPerson);
                }

                $neighbourhoods = Neighbourhood::all()->random();
                $partner->neighbourhood()->associate($neighbourhoods);
                $partner->save();
            });
        }
    }
}
