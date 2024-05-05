<?php

/** Rendering */

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\ActivityResource\Pages\CreateActivity;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Coordinator;
use App\Models\Neighbourhood;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Task;
use function Pest\Livewire\livewire;

it('can render Activity create Form', function () {
    $activity = Activity::factory()->create();
    $this->get(ActivityResource::getUrl('create', ['record' => $activity->getKey()]))->assertSuccessful();
});

it('can create Activity', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $neighbourhood = Neighbourhood::factory()->create();
    $coordinator = Coordinator::factory()->create();
    $partner = Partner::factory()->create();
    $activity = Activity::factory()->make();

    livewire(CreateActivity::class)
        ->fillForm([
            'name' => $activity->name,
            'project_id' => $project->getKey(),
            'task_id' => $task->getKey(),
            'date' => $activity->date,
            'contact_person_id' => $contactPerson->getKey(),
            'neighbourhood_id' => [$neighbourhood->getKey()],
            'coordinator_id' => [$coordinator->getKey()],
            'partners_id' => [$partner->getKey()],
            'comment' => $activity->comment,
        ])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas(Activity::class, [
        'name' => $activity->name,
        'project_id' => $project->getKey(),
        'task_id' => $task->getKey(),
        'date' => $activity->date,
        'comment' => $activity->comment,
    ]);
});

it('can disable contact_person_id field on Activity', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->create();
    $neighbourhood = Neighbourhood::factory()->create();
    $coordinator = Coordinator::factory()->create();
    $activity = Activity::factory()->make();

    livewire(CreateActivity::class)
        ->fillForm([
            'name' => $activity->name,
            'project_id' => $project->getKey(),
            'task_id' => $task->getKey(),
            'date' => $activity->date,
            'neighbourhood_id' => [$neighbourhood->getKey()],
            'coordinator_id' => [$coordinator->getKey()],
            'partners_id' => [],
            'comment' => $activity->comment,
        ])->assertFormFieldIsDisabled('contact_person_id');
});

it('can enable contact_person_id field on Activity', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->create();
    $neighbourhood = Neighbourhood::factory()->create();
    $coordinator = Coordinator::factory()->create();
    $partner = Partner::factory()->create();
    $activity = Activity::factory()->make();

    livewire(CreateActivity::class)
        ->fillForm([
            'name' => $activity->name,
            'project_id' => $project->getKey(),
            'task_id' => $task->getKey(),
            'date' => $activity->date,
            'neighbourhood_id' => [$neighbourhood->getKey()],
            'coordinator_id' => [$coordinator->getKey()],
            'partners_id' => [$partner->getKey()],
            'comment' => $activity->comment,
        ])->assertFormFieldIsEnabled('contact_person_id');
});

it('can validate create Activity form', function () {
    livewire(CreateActivity::class)
        ->fillForm([
            'name' => null,
            'project_id' => null,
            'task_id' => null,
            'date' => null,
            'neighbourhood_id' => [],
            'coordinator_id' => [],
            'partners_id' => [],
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'project_id' => 'required',
            'task_id' => 'required',
            'date' => 'required',
            'neighbourhood_id' => 'required',
            'coordinator_id' => 'required',
            'partners_id' => 'required',
        ]);
});

it('can render Activity edit Form', function () {
    $activity = Activity::factory()->create();
    $this->get(ActivityResource::getUrl('edit', ['record' => $activity->getKey()]))->assertSuccessful();
});
