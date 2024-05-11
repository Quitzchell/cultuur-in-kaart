<?php

use App\Filament\Resources\ContactPersonResource\Pages\ViewContactPerson;
use App\Filament\Resources\ContactPersonResource\RelationManagers\ProjectRelationManager;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use App\Models\Project;
use function Pest\Livewire\livewire;

/** Render */
it('can render related projects', function () {
    $contactPerson = ContactPerson::factory()->create();
    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->assertSuccessful();
});

it('can list related projects', function () {
    $partner = partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $contactPerson->partners()->attach($partner);

    $projects = Project::factory(3)->create()
        ->each(function (Project $project) use ($partner, $contactPerson) {
            $project->activities()->saveMany(Activity::factory(3)->create()->each(function (Activity $activity) use ($contactPerson, $partner) {
                $activity->partners()->attach($partner);
                $activity->contactPerson()->associate($contactPerson);
                $activity->save();
            }));
        });

    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->assertCanSeeTableRecords($projects)
        ->assertCountTableRecords(3)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('start_date')
        ->assertTableColumnExists('start_date')
        ->assertCanRenderTableColumn('end_date')
        ->assertTableColumnExists('end_date');
});

/** Sort */
it('can sort related projects by start date', function () {
    $partner = partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $contactPerson->partners()->attach($partner);

    $projects = Project::factory(3)->create()
        ->each(function (Project $project) use ($partner, $contactPerson) {
            $project->activities()->saveMany(Activity::factory(3)->create()->each(function (Activity $activity) use ($contactPerson, $partner) {
                $activity->partners()->attach($partner);
                $activity->contactPerson()->associate($contactPerson);
                $activity->save();
            }));
        });

    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->sortTable('start_date')
        ->assertCanSeeTableRecords($projects->sortBy('start_date'), inOrder: true)
        ->sortTable('start_date', 'desc')
        ->assertCanSeeTableRecords($projects->sortByDesc('start_date'), inOrder: true);
});

/** Search */
it('can search related Projects by name', function () {
    $partner = partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $contactPerson->partners()->attach($partner);

    $projects = Project::factory(3)->create()->each(function (Project $project) use ($partner, $contactPerson) {
        $project->activities()->saveMany(Activity::factory(3)->create()->each(function (Activity $activity) use ($contactPerson, $partner) {
            $activity->partners()->attach($partner);
            $activity->contactPerson()->associate($contactPerson);
            $activity->save();
        }));
    });

    $name = $projects->first()->name;
    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->searchTable($name)
        ->assertCanSeeTableRecords($projects->where('name', $name))
        ->assertCanNotSeeTableRecords($projects->where('name', '!==', $name));
});
