<?php

use App\Filament\Resources\ActivityResource\Pages\ViewActivities;
use App\Filament\Resources\ActivityResource\RelationManagers\RelatedActivityRelationManager;
use App\Models\Activity;
use App\Models\Neighbourhood;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Task;
use function Pest\Livewire\livewire;

/** Render */
it('can render related Activities', function () {
    $activities = Activity::factory()->create();
    livewire(RelatedActivityRelationManager::class, [
        'ownerRecord' => $activities->first(),
        'pageClass' => ViewActivities::class
    ])->assertSuccessful();
});

it('can list related Activities', function () {
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => Project::factory()->create()->getKey()])
        ->each(function (Activity $activity) use ($tasks) {
            $activity->task()->associate($tasks->random());
            $activity->save();
        });

    livewire(RelatedActivityRelationManager::class, [
        'ownerRecord' => $activities->first(),
        'pageClass' => ViewActivities::class
    ])->assertCanSeeTableRecords($activities)
        ->assertCountTableRecords(9)
        ->assertCanRenderTableColumn('date')
        ->assertTableColumnExists('date')
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('task.name')
        ->assertTableColumnExists('task.name')
        ->assertCanRenderTableColumn('neighbourhoods.name')
        ->assertTableColumnExists('neighbourhoods.name')
        ->assertCanRenderTableColumn('partners.name')
        ->assertTableColumnExists('partners.name');
});

/** Sort */
it('can sort related Activities by date', function () {
    $activities = Activity::factory(10)
        ->create(['project_id' => Project::factory()->create()->getKey()]);

    $activity = $activities->first();
    $activities = $activities->where('id', '!==', $activity->getKey());
    livewire(RelatedActivityRelationManager::class, [
        'ownerRecord' => $activity,
        'pageClass' => ViewActivities::class
    ])->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

/** Filter */
it('can filter RelatedActivities on Task', function () {
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => Project::factory()->create()->getKey()])
        ->each(function (Activity $activity) use ($tasks) {
            $activity->task()->associate($tasks->random());
            $activity->save();
        });

    $activity = $activities->first();
    $activities = $activities->where('id', '!==', $activity->getKey());
    livewire(RelatedActivityRelationManager::class, [
        'ownerRecord' => $activity,
        'pageClass' => ViewActivities::class
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('task_id', $activity->task_id)
        ->assertCanSeeTableRecords($activities->where('task_id', $activity->task_id))
        ->assertCanNotSeeTableRecords($activities->where('task_id', '!==', $activity->task_id));
});

it('can filter RelatedActivities on Neighbourhood', function () {
    $neighbourhoods = Neighbourhood::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => Project::factory()->create()->getKey()])
        ->each(function (Activity $activity) use ($neighbourhoods) {
            $activity->neighbourhoods()->attach($neighbourhoods->random(2));
        });

    $activity = $activities->first();
    $neighbourhood = $activity->neighbourhoods->first();
    $activities = $activities->where('id', '!==', $activity->getKey());
    $filteredActivities = $activities->filter(function (Activity $iterateActivity) use ($neighbourhood, $activity) {
        return $iterateActivity->neighbourhoods->contains($neighbourhood->getKey())
            && $iterateActivity->getKey() !== $activity->getKey();
    });

    livewire(RelatedActivityRelationManager::class, [
        'ownerRecord' => $activity,
        'pageClass' => ViewActivities::class
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('neighbourhoods_id', $activity->neighbourhood_id)
        ->assertCanSeeTableRecords($filteredActivities)
        ->assertCanNotSeeTableRecords($filteredActivities->diff($filteredActivities));
});

it('can filter RelatedActivities on Partner', function () {
    $partners = Partner::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => Project::factory()->create()->getKey()])
        ->each(function (Activity $activity) use ($partners) {
            $activity->partners()->attach($partners->random(2));
        });

    $activity = $activities->first();
    $partner = $activity->partners->first();
    $activities = $activities->where('id', '!==', $activity->getKey());
    $filteredActivities = $activities->filter(function (Activity $iterateActivity) use ($partner, $activity) {
        return $iterateActivity->partners->contains($partner->getKey())
            && $iterateActivity->getKey() !== $activity->getKey();
    });

    livewire(RelatedActivityRelationManager::class, [
        'ownerRecord' => $activity,
        'pageClass' => ViewActivities::class
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('partners_id', $activity->partner_id)
        ->assertCanSeeTableRecords($filteredActivities)
        ->assertCanNotSeeTableRecords($filteredActivities->diff($filteredActivities));
});
