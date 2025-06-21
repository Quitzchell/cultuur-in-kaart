<?php

use App\Filament\RelationManagers\ContactPersonResource\ActivityRelationManager;
use App\Filament\Resources\ContactPersonResource\Pages\ViewContactPerson;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use App\Models\Task;

use function Pest\Livewire\livewire;

/** Render */
it('can render related Activities', function () {
    $contactPerson = ContactPerson::factory()->create();
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->assertSuccessful();
});

it('can list related Activities', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $task = Task::factory()->create();
    $activities = Activity::factory(10)->create();

    foreach ($activities as $activity) {
        $activity->task()->associate($task);
        $activity->activityPartnerContactPerson()->create([
            'partner_id' => $partner->getKey(),
            'contact_person_id' => $contactPerson->getKey(),
        ]);
    }

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->assertCanSeeTableRecords($activities)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('date')
        ->assertTableColumnExists('date')
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('task.name')
        ->assertTableColumnExists('task.name');
});

/** Sort */
it('can sort related Activities by date', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $task = Task::factory()->create();
    $activities = Activity::factory(10)->create();

    foreach ($activities as $activity) {
        $activity->task()->associate($task);
        $activity->activityPartnerContactPerson()->create([
            'partner_id' => $partner->getKey(),
            'contact_person_id' => $contactPerson->getKey(),
        ]);
    }

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

/** Search */
it('can search related Activities by name', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $task = Task::factory()->create();
    $activities = Activity::factory(10)->create();

    foreach ($activities as $activity) {
        $activity->task()->associate($task);
        $activity->activityPartnerContactPerson()->create([
            'partner_id' => $partner->getKey(),
            'contact_person_id' => $contactPerson->getKey(),
        ]);
    }

    $name = $activities->first()->name;
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $contactPerson,
        'pageClass' => ViewContactPerson::class,
    ])->searchTable($name)
        ->assertCanSeeTableRecords($activities->where('name', $name))
        ->assertCanNotSeeTableRecords($activities->where('name', '!=', $name));
});
