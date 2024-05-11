<?php

use App\Filament\Resources\ContactPersonResource\Pages\ViewContactPerson;
use App\Filament\Resources\ContactPersonResource\RelationManagers\ActivityRelationManager;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use App\Models\Task;
use function Pest\Livewire\livewire;

/** Render */
it('can can render related Activities', function () {
    $contactPerson = ContactPerson::factory()->create();
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->assertSuccessful();
});

it('can list related Activities', function () {
    $partner = partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $contactPerson->partners()->attach($partner);

    $tasks = Task::factory()->create();
    $activities = Activity::factory(10)->create()
        ->each(function (Activity $activity) use ($tasks, $partner, $contactPerson) {
            $activity->task()->associate($tasks);
            $activity->partners()->attach($partner);
            $activity->contactPerson()->associate($contactPerson);
            $activity->save();
        });

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->assertCanSeeTableRecords($activities)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('date')
        ->assertTableColumnExists('date')
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name');
});

/** Sort */
it('can sort related Activities', function () {
    $partner = partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $contactPerson->partners()->attach($partner);

    $activities = Activity::factory(10)->create()
        ->each(function (Activity $activity) use ($partner, $contactPerson) {
            $activity->partners()->attach($partner);
            $activity->contactPerson()->associate($contactPerson);
            $activity->save();
        });

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

/** Search */
it('can search related Activities', function () {
    $partner = partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $contactPerson->partners()->attach($partner);

    $tasks = Task::factory()->create();
    $activities = Activity::factory(10)->create()
        ->each(function (Activity $activity) use ($tasks, $partner, $contactPerson) {
            $activity->task()->associate($tasks);
            $activity->partners()->attach($partner);
            $activity->contactPerson()->associate($contactPerson);
            $activity->save();
        });

    $name = $activities->first()->name;
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->searchTable($name)
        ->assertCanSeeTableRecords($activities->where('name', $name))
        ->assertCanNotSeeTableRecords($activities->where('name', '!==', $name));
});
