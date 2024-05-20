<?php

use App\Filament\RelationManagers\ProjectResource\ActivityRelationManager;
use App\Filament\Resources\ProjectResource\Pages\ViewProject;
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
        'pageClass' => ViewProject::class,
    ])->assertSuccessful();
});

it('can list related Activities', function () {
    $project = Project::factory()->create();
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create([
        'project_id' => $project->getKey(),
    ])->each(function (Activity $activity) use ($tasks) {
        $activity->task()->associate($tasks->random());
        $activity->save();
    });

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
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
        'pageClass' => ViewProject::class,
    ])->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

/** Filter */
it('can filter related Activities by Task', function () {
    $project = Project::factory()->create();
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create(['project_id' => $project->getKey()])
        ->each(function (Activity $activity) use ($tasks) {
            $activity->task()->associate($tasks->random());
            $activity->save();
        });

    $activity = $activities->first();
    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('task', $activity->task_id)
        ->assertCanSeeTableRecords($activities->where('task_id', $activity->task_id))
        ->assertCanNotSeeTableRecords($activities->where('task_id', '!==', $activity->task_id));
});

it('can filter related Activities by Partners', function () {
    $project = Project::factory()->create();
    $partners = Partner::factory(2)->create();

    $activities = Activity::factory(10)->create(['project_id' => $project->getKey()])
        ->each(function (Activity $activity) use ($partners) {
            $activity->partners()->attach($partners->random());
        });

    $partner = Partner::first();
    $filteredActivities = $activities->filter(function (Activity $activity) use ($partner, $project) {
        return $activity->project_id === $project->getKey() && $activity->partners->contains($partner);
    });

    livewire(ActivityRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
    ])->assertCanSeeTableRecords($activities)
        ->filterTable('partner', $partner->getKey())
        ->assertCanSeeTableRecords($filteredActivities)
        ->assertCanNotSeeTableRecords($activities->diff($filteredActivities));
});
