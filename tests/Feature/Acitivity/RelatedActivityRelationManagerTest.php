<?php

use App\Filament\RelationManagers\ActivityResource\ActivityRelationManager;
use App\Filament\Resources\ActivityResource\Pages\ViewActivity;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use function Pest\Livewire\livewire;

/** Render */
it('can render related Activities', function () {
    $activity = Activity::factory()->create();
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $activity,
        'pageClass' => ViewActivity::class
    ])->assertSuccessful();
});

it('can list related Activities', function () {
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => Project::factory()->create()->getKey()])
        ->each(function (Activity $activity) use ($tasks) {
            $activity->task()->associate($tasks->random());
            $activity->save();
        });

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $activities->first(),
        'pageClass' => ViewActivity::class
    ])->assertCanSeeTableRecords($activities)
        ->assertCountTableRecords(9)
        ->assertCanRenderTableColumn('date')
        ->assertTableColumnExists('date')
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('task.name')
        ->assertTableColumnExists('task.name');
});

/** Sort */
it('can sort related Activities by date', function () {
    $activities = Activity::factory(10)
        ->create(['project_id' => Project::factory()->create()->getKey()]);

    $activity = $activities->first();
    $activities = $activities->where('id', '!==', $activity->getKey());
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $activity,
        'pageClass' => ViewActivity::class
    ])->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

/** Filter */
it('can filter related Activities on Task', function () {
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => Project::factory()->create()->getKey()])
        ->each(function (Activity $activity) use ($tasks) {
            $activity->task()->associate($tasks->random());
            $activity->save();
        });

    $activity = $activities->first();
    $activities = $activities->where('id', '!==', $activity->getKey());
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $activity,
        'pageClass' => ViewActivity::class
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('task', $activity->task_id)
        ->assertCanSeeTableRecords($activities->where('task_id', $activity->task_id))
        ->assertCanNotSeeTableRecords($activities->where('task_id', '!==', $activity->task_id));
});
