<?php

use App\Filament\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\Project;
use function Pest\Livewire\livewire;

it('can render Activity List', function () {
    $this->get(ActivityResource::getUrl())->assertSuccessful();
});

it('can list activities', function () {
    $activities = Activity::factory()
        ->count(10)
        ->create();

    livewire(ActivityResource\Pages\ListActivities::class)
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

it('can sort activities by date', function () {
    $activities = Activity::factory()
        ->count(10)
        ->create();

    livewire(ActivityResource\Pages\ListActivities::class)
        ->sortTable('date')
        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
        ->sortTable('date', 'desc')
        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
});

it('can search activities by name', function () {
    $activities = Activity::factory()
        ->count(10)
        ->create();

    $name = $activities->first()->name;

    livewire(ActivityResource\Pages\ListActivities::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($activities->where('name', $name))
        ->assertCanNotSeeTableRecords($activities->where('name', '!==', $name));
});

it('can search activities by project name', function () {
    $projects = Project::factory()->count(4)->has(Activity::factory()->count(10))->create();

    $project = $projects->first();

    livewire(ActivityResource\Pages\ListActivities::class)
        ->searchTable($project->name)
        ->assertCanSeeTableRecords($project->activities->where('project_id', $project->getKey()))
        ->assertCanNotSeeTableRecords($project->activities->where('project_id', '!==', $project->getKey()));
});
