<?php

use App\Filament\RelationManagers\PartnerResource\ActivityRelationManager;
use App\Filament\Resources\PartnerResource\Pages\ViewPartner;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use App\Models\Task;

use function Pest\Livewire\livewire;

/** Render */
it('can render related Activities', function () {
    $partner = Partner::factory()->create();
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class,
    ])->assertSuccessful();
});

it('can list related Activities', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $activities = Activity::factory(10)->create();
    foreach ($activities as $activity) {
        $activity->activityPartnerContactPerson()->create([
            'partner_id' => $partner->getKey(),
            'contact_person_id' => $contactPerson->getKey(),
        ]);
        $activity->partners()->attach($partner->getKey());
        $activity->task()->associate(Task::factory()->create());
        $activity->save();
    }

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class,
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
it('can sort related Activities by Date', function () {
    $partner = Partner::factory()->create();
    $activities = Activity::factory(10)->create()
        ->each(function (Activity $activity) use ($partner) {
            $activity->partners()->attach($partner);
        });

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class,
    ])->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

/** Search */
it('can search related Activities by name', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $activities = Activity::factory(4)->create();
    foreach ($activities as $activity) {
        $activity->activityPartnerContactPerson()->create([
            'partner_id' => $partner->getKey(),
            'contact_person_id' => $contactPerson->getKey(),
        ]);
        $activity->partners()->attach($partner->getKey());
        $activity->task()->associate(Task::factory()->create());
        $activity->save();
    }

    $name = $activities->first()->name;
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class,
    ])->searchTable($name)
        ->assertCanSeeTableRecords($activities->where('name', $name))
        ->assertCanNotSeeTableRecords($activities->where('name', '!==', $name));
});

/** Filter */
it('can filter related Activities by tasks', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $activities = Activity::factory(10)->create();
    $tasks = Task::factory(2)->create();
    foreach ($activities as $key => $activity) {
        $activity->activityPartnerContactPerson()->create([
            'partner_id' => $partner->getKey(),
            'contact_person_id' => $contactPerson->getKey(),
        ]);
        $activity->partners()->attach($partner->getKey());
        $activity->task()->associate($tasks[$key % 2]->getKey());
        $activity->save();
    }

    $task = $tasks->first();
    $filteredActivities = $activities->filter(function ($activity) use ($task) {
        return $activity->task_id === $task->id;
    });
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class,
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('task', $task->getKey())
        ->assertCanSeeTableRecords($filteredActivities)
        ->assertCanNotSeeTableRecords($activities->diff($filteredActivities));
});
