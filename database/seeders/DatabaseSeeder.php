<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Coordinator;
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
        ]);

        if (app()->environment() !== 'production') {
            Coordinator::factory(3)->create();
            Project::factory(10)->create()->each(function (Project $project) {
                $contactPeople = ContactPerson::factory(5)->create();
                $project->activities()->saveMany(
                    Activity::factory(5)->make()->each(function (Activity $activity) use ($contactPeople) {
                        $contactPerson = $contactPeople->random();
                        $activity->contactPerson()->associate($contactPerson);
                        $activity->save();

                        $neighbourhood = Neighbourhood::all()->random();
                        $activity->neighbourhoods()->attach($neighbourhood);
                    })
                );
            });

            $contactPeople = ContactPerson::all();
            Partner::factory(10)->recycle($contactPeople)->create()->each(function (Partner $partner) use ($contactPeople) {
                $contactPerson = $contactPeople->random();
                $partner->contactPeople()->attach($contactPerson);
            });
        }
    }
}
