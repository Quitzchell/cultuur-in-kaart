<?php

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Models\Activity;
use App\Models\Neighbourhood;
use App\Models\Project;

use function Pest\Livewire\livewire;

/** Render */
it('can render Project list', function () {
    $this->get(ProjectResource::getUrl())->assertSuccessful();
});

it('can list Projects', function () {
    $projects = Project::factory(10)->create();

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('neighbourhoods.name')
        ->assertTableColumnExists('neighbourhoods.name')
        ->assertCanRenderTableColumn('start_date')
        ->assertTableColumnExists('start_date')
        ->assertCanRenderTableColumn('end_date')
        ->assertTableColumnExists('end_date');
});

/** Sort */
it('can sort Projects by start date', function () {
    $projects = Project::factory(10)->create();

    livewire(ListProjects::class)
        ->sortTable('start_date')
        ->assertCanSeeTableRecords($projects->sortBy('start_date'), inOrder: true)
        ->sortTable('start_date', 'desc')
        ->assertCanSeeTableRecords($projects->sortByDesc('start_date'), inOrder: true);
});

it('can sort Projects by end date', function () {
    $projects = Project::factory(10)->create();

    livewire(ListProjects::class)
        ->sortTable('end_date')
        ->assertCanSeeTableRecords($projects->sortBy('end_date'), inOrder: true)
        ->sortTable('end_date', 'desc')
        ->assertCanSeeTableRecords($projects->sortByDesc('end_date'), inOrder: true);
});

/** Search */
it('can filter Projects by name', function () {
    $projects = Project::factory(10)->create();
    $name = $projects->first()->name;

    livewire(ListProjects::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($projects->where('name', $name))
        ->assertCanNotSeeTableRecords($projects->where('name', '!==', $name));
});

/** Filter */
it('can filter Projects by neighbourhood', function () {
    $projects = Project::factory(2)->create();
    $neighbourhoods = Neighbourhood::factory(2)->create();
    $activities = Activity::factory(40)->create();
    foreach ($activities as $key => $activity) {
        $activity->neighbourhood()->associate($neighbourhoods[$key % 2]);
        $activity->project()->associate($projects[$key % 2]);
        $activity->save();
    }

    $neighbourhood = $projects->first()->neighbourhoods->first();
    $filteredProjects = $projects->filter(function (Project $project) use ($neighbourhood) {
        return $project->neighbourhoods->contains($neighbourhood);
    });

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->filterTable('neighbourhoods', $neighbourhood->getKey())
        ->assertCanSeeTableRecords($filteredProjects)
        ->assertCanNotSeeTableRecords($projects->diff($filteredProjects));
});
