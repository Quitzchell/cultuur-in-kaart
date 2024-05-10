<?php

use App\Filament\Resources\ProjectResource\Pages\ViewProjects;
use App\Filament\Resources\ProjectResource\RelationManagers\ActivityRelationManager;
use App\Models\Activity;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Task;
use function Pest\Livewire\livewire;

/** Render */
it('can render related Activities', function () {
    $project = Project::factory()->create();
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProjects::class
    ])->assertSuccessful();
});

it('can list related Activities', function () {
    $project = Project::factory()->create();
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => $project->getKey()])->each(function (Activity $activity) use ($tasks) {
        $activity->task()->associate($tasks->random());
        $activity->save();
    });

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProjects::class
    ])->assertCanSeeTableRecords($activities)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('date')
        ->assertTableColumnExists('date')
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('task.name')
        ->assertTableColumnExists('task.name')
        ->assertCanRenderTableColumn('partners.name')
        ->assertTableColumnExists('partners.name');
});

/** Sort */
it('can sort related Activities by date', function () {
    $project = Project::factory()->create();
    $activities = Activity::factory(10)->create(['project_id' => $project->getKey()]);

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProjects::class
    ])->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

/** Filter */
it('can filter related Activities by Task', function () {
    $project = Project::factory()->create();
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => $project->getKey()])->each(function (Activity $activity) use ($tasks) {
        $activity->task()->associate($tasks->random());
        $activity->save();
    });

    $activity = $activities->first();
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProjects::class
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('task', $activity->task_id)
        ->assertCanSeeTableRecords($activities->where('task_id', $activity->task_id))
        ->assertCanNotSeeTableRecords($activities->where('task_id', '!==', $activity->task_id));
});

it('can filter related Activities by Partners', function () {
    $projects = Project::factory(2)->create();
    $partners = Partner::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => $projects->random()])
        ->each(function (Activity $activity) use ($partners) {
            $activity->partners()->attach($partners->random(2));
        });

    $activity = $activities->first();
    $partner = $activity->partners->first();
    $filteredActivities = $activities->filter(function (Activity $iterateActivity) use ($partner, $activity) {
        return $iterateActivity->partners->contains($partner->getKey());
    });

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $projects->first(),
        'pageClass' => ViewProjects::class
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('partners', $activity->partner_id)
        ->assertCanSeeTableRecords($filteredActivities)
        ->assertCanNotSeeTableRecords($filteredActivities->diff($filteredActivities));
});
