<?php

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\ActivityResource\Pages\ListActivities;
use App\Models\Activity;
use App\Models\Neighbourhood;
use App\Models\Project;
use App\Models\Task;
use function Pest\Livewire\livewire;

/** Render */
it('can render Activity List', function () {
    $this->get(ActivityResource::getUrl())->assertSuccessful();
});

it('can list Activities', function () {
    $activities = Activity::factory(10)->create();

    livewire(ListActivities::class)
        ->assertCanSeeTableRecords($activities)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('date')
        ->assertTableColumnExists('date')
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('project.name')
        ->assertTableColumnExists('project.name')
        ->assertCanRenderTableColumn('neighbourhoods.name')
        ->assertTableColumnExists('neighbourhoods.name');
});

/** Sort */
it('can sort Activities by date', function () {
    $activities = Activity::factory(5)->create();

    livewire(ListActivities::class)
        ->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

/** Search */
it('can search Activities by name', function () {
    $activities = Activity::factory(10)->create();
    $name = $activities->first()->name;

    livewire(ListActivities::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($activities->where('name', $name))
        ->assertCanNotSeeTableRecords($activities->where('name', '!==', $name));
});

it('can search Activities by project name', function () {
    $projects = Project::factory(4)->has(Activity::factory(5))->create();
    $project = $projects->first();

    livewire(ListActivities::class)
        ->searchTable($project->name)
        ->assertCanSeeTableRecords($project->activities->where('project_id', $project->getKey()))
        ->assertCanNotSeeTableRecords($project->activities->where('project_id', '!==', $project->getKey()));
});

it('can search Activities by neighbourhood name', function () {
    $neighbourhoods = Neighbourhood::factory(10)->create();
    $activities = Activity::factory(5)->create()->each(function (Activity $activity) use ($neighbourhoods) {
        $activity->neighbourhoods()->attach($neighbourhoods->random(2));
    });

    $neighbourhood = $neighbourhoods->first();
    $filteredActivities = $activities->filter(function (Activity $activity) use ($neighbourhood) {
        return $activity->neighbourhoods->contains($neighbourhood);
    });

    livewire(ListActivities::class)
        ->searchTable($neighbourhood->name)
        ->assertCanSeeTableRecords($filteredActivities)
        ->assertCanNotSeeTableRecords($activities->diff($filteredActivities));
});

/** Filtering */
it('can filter Activities by Project', function () {
    $projects = Project::factory(4)->create();
    $activities = Activity::factory(10)->create()->each(function (Activity $activity) use ($projects) {
        $activity->project()->associate($projects->random());
        $activity->save();
    });
    $project = $projects->first();

    livewire(ListActivities::class)
        ->assertCanSeeTableRecords($activities)
        ->filterTable('project_id', $project->getKey())
        ->assertCanSeeTableRecords($activities->where('project_id', $project->getKey()))
        ->assertCanNotSeeTableRecords($activities->where('project_id', '!==', $project->getKey()));
});

it('can filter Activities by Neighbourhood', function () {
    $neighbourhoods = Neighbourhood::factory(10)->create();
    $activities = Activity::factory(10)->create()->each(function (Activity $activity) use ($neighbourhoods) {
        $activity->neighbourhoods()->attach($neighbourhoods->random(2));
    });

    $neighbourhood = $activities->first()->neighbourhoods->first();
    $filteredActivities = $activities->filter(function (Activity $activity) use ($neighbourhood) {
        return $activity->neighbourhoods->contains($neighbourhood);
    });

    livewire(ListActivities::class)
        ->assertCanSeeTableRecords($activities)
        ->filterTable('neighbourhood_id', $neighbourhood->getKey())
        ->assertCanSeeTableRecords($filteredActivities)
        ->assertCanNotSeeTableRecords($activities->diff($filteredActivities));
});

it('can filter Activities by Task', function () {
    $tasks = Task::factory(10)->create();
    $activities = Activity::factory(10)->create()->each(function (Activity $activity) use ($tasks) {
        $activity->task()->associate($tasks->random());
        $activity->save();
    });
    $task = $tasks->first();

    livewire(ListActivities::class)
        ->assertCanSeeTableRecords($activities)
        ->filterTable('task_id', $task->getKey())
        ->assertCanSeeTableRecords($activities->where('task_id', $task->getKey()))
        ->assertCanNotSeeTableRecords($activities->where('task_id', '!==', $task->getKey()));
});

it('can filter Activities by date from', function () {
    $activities = Activity::factory(10)->create();
    $sortedActivities = $activities->sortBy('date');
    $dateFrom = $sortedActivities->get(5)->date;

    livewire(ListActivities::class)
        ->assertCanSeeTableRecords($activities)
        ->filterTable('date', ['date_from' => $dateFrom, 'date_until' => null])
        ->assertCanSeeTableRecords($activities->where('date', '>=', $dateFrom))
        ->assertCanNotSeeTableRecords($activities->where('date', '<', $dateFrom));
});

it('can filter activities by date until', function () {
    $activities = Activity::factory(10)->create();
    $sortedActivities = $activities->sortBy('date');
    $dateUntil = $sortedActivities->get(5)->date;

    livewire(ListActivities::class)
        ->assertCanSeeTableRecords($activities)
        ->filterTable('date', ['date_from' => null, 'date_until' => $dateUntil])
        ->assertCanSeeTableRecords($activities->where('date', '<=', $dateUntil))
        ->assertCanNotSeeTableRecords($activities->where('date', '>', $dateUntil));
});

it('can filter activities by date between', function () {
    $activities = Activity::factory(10)->create();
    $sortedActivities = $activities->sortBy('date');
    $dateFrom = $sortedActivities->get(3)->date;
    $dateUntil = $sortedActivities->get(9)->date;

    livewire(ListActivities::class)
        ->assertCanSeeTableRecords($activities)
        ->filterTable('date', ['date_from' => $dateFrom, 'date_until' => $dateUntil])
        ->assertCanSeeTableRecords($activities->where('date', '>=', $dateFrom)->where('date', '<=', $dateUntil))
        ->assertCanNotSeeTableRecords($activities->where('date', '<', $dateFrom)->where('date', '>', $dateUntil));
});
