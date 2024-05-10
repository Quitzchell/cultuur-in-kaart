<?php

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Models\Activity;
use App\Models\ActivityNeighbourhood;
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
        ->assertCanRenderTableColumn('neighbourhoods.neighbourhood.name')
        ->assertTableColumnExists('neighbourhoods.neighbourhood.name')
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

it('can search Projects by neighbourhood', function () {
    $projects = Project::factory(10)->create();
    $neighbourhoods = Neighbourhood::factory(10)->create();
    Activity::factory(40)->create()->each(function (Activity $activity) use ($projects, $neighbourhoods) {
        $activity->neighbourhoods()->attach($neighbourhoods->random(4));
        $activity->project()->associate($projects->random());
        $activity->save();
    });

    $neighbourhood = $projects->first()->neighbourhoods->first()->neighbourhood;
    $filteredProjects = $projects->filter(function (Project $project) use ($neighbourhood) {
        return $project->neighbourhoods->some(function (ActivityNeighbourhood $activityNeighbourhood) use ($neighbourhood) {
            return str_contains($activityNeighbourhood->neighbourhood->name, $neighbourhood->name);
        });
    });

    livewire(ListProjects::class)
        ->searchTable($neighbourhood->name)
        ->assertCanSeeTableRecords($filteredProjects)
        ->assertCanNotSeeTableRecords($projects->diff($filteredProjects));
});

/** Filter */
it('can filter Projects by neighbourhood', function () {
    $projects = Project::factory(10)->create();
    $neighbourhoods = Neighbourhood::factory(10)->create();
    Activity::factory(40)->create()->each(function (Activity $activity) use ($projects, $neighbourhoods) {
        $activity->neighbourhoods()->attach($neighbourhoods->random(4));
        $activity->project()->associate($projects->random());
        $activity->save();
    });

    $neighbourhood = $projects->first()->neighbourhoods->first()->neighbourhood;
    $filteredProjects = $projects->filter(function (Project $project) use ($neighbourhood) {
        return $project->neighbourhoods->some(function (ActivityNeighbourhood $activityNeighbourhood) use ($neighbourhood) {
            return $activityNeighbourhood->neighbourhood->getKey() === $neighbourhood->getKey();
        });
    });

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->filterTable('neighbourhood', $neighbourhood->getKey())
        ->assertCanSeeTableRecords($filteredProjects)
        ->assertCanNotSeeTableRecords($projects->diff($filteredProjects));
});
