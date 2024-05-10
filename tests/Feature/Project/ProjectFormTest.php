<?php

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Models\Coordinator;
use App\Models\Project;
use Filament\Actions\DeleteAction;
use function Pest\Livewire\livewire;

/** Render */
it('can render Project create form', function () {
    $this->get(ProjectResource::getUrl('create'))->assertSuccessful();
});

it('can render Project edit form', function () {
    $project = Project::factory()->create();
    $this->get(ProjectResource::getUrl('edit', ['record' => $project->getKey()]))->assertSuccessful();
});

/** Create */
it('can create Project', function () {
    $project = Project::factory()->make();
    $coordinators = Coordinator::factory(3)->create();

    livewire(CreateProject::class)
        ->fillForm([
            'name' => $project->name,
            'project_number' => $project->project_number,
            'coordinator_id' => $coordinators->map(fn(Coordinator $coordinator) => $coordinator->getKey())->toArray(),
            'primary_coordinator_id' => $coordinators->first()->getKey(),
            'start_date' => $project->start_date,
            'end_date' => $project->end_date,
            'budget_spend' => $project->budget_spend,
        ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Project::class, [
        'name' => $project->name,
        'project_number' => $project->project_number,
        'start_date' => $project->start_date,
        'end_date' => $project->end_date,
        'budget_spend' => $project->budget_spend * 100,
        'primary_coordinator_id' => $coordinators->first()->getKey(),
    ]);

    $savedProject = $project->first();
    $this->assertDatabaseHas('coordinator_project', [
        'project_id' => $savedProject->getKey(),
        'coordinator_id' => $coordinators->first()->getKey(),
    ]);
});

/** Validate */
it('can validate Project form', function () {
    livewire(CreateProject::class)
        ->fillForm([
            'name' => null,
            'project_number' => null,
            'coordinator_id' => [],
            'primary_coordinator_id' => null,
            'start_date' => null,
            'end_date' => null,
            'budget_spend' => 'asdfghjk',
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'project_number' => 'required',
            'coordinator_id' => 'required',
            'start_date' => 'required',
            'budget_spend' => 'numeric',
        ]);
});

/** Edit */
it('can update Project', function () {
    $project = Project::factory()->create();
    $coordinators = Coordinator::factory(3)->create();
    $project->coordinators()->attach($coordinators);
    $project->coordinator()->associate($coordinators->first());
    $project->save();

    $newProject = Project::factory()->make();
    $newCoordinator = Coordinator::factory()->create();
    $newCoordinators = $project->coordinators->add($newCoordinator);

    livewire(EditProject::class, [
        'record' => $project->getKey()
    ])->fillForm([
        'name' => $newProject->name,
        'project_number' => $newProject->project_number,
        'coordinator_id' => $newCoordinators->map(fn(Coordinator $coordinator) => $coordinator->getKey())->toArray(),
        'primary_coordinator_id' => $newCoordinator->getKey(),
        'start_date' => $newProject->start_date,
        'end_date' => $newProject->end_date,
        'budget_spend' => $newProject->budget_spend,
    ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Project::class, [
        'name' => $newProject->name,
        'project_number' => $newProject->project_number,
        'primary_coordinator_id' => $newCoordinator->getKey(),
        'start_date' => $newProject->start_date,
        'end_date' => $newProject->end_date,
        'budget_spend' => $newProject->budget_spend * 100,
    ]);

    $savedProject = $project->first();
    $this->assertDatabaseHas('coordinator_project', [
        'project_id' => $savedProject->getKey(),
        'coordinator_id' => $newCoordinator->getKey(),
    ]);
});

/** Delete */
it('can delete Project', function () {
    $project = Project::factory()->create();
    livewire(EditProject::class, [
        'record' => $project->getKey()
    ])->callAction(DeleteAction::class);

    $this->assertModelMissing($project);
});

/** Other */
it('can enable primary Coordinator', function () {
    $coordinators = Coordinator::factory(3)->create();
    livewire(CreateProject::class)
        ->fillForm([
            'coordinator_id' => $coordinators->map(fn(Coordinator $coordinator) => $coordinator->getKey())->toArray(),
        ])->assertFormFieldIsEnabled('primary_coordinator_id');
});
