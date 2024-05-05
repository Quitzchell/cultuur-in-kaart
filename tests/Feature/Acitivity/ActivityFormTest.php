<?php

/** Rendering */

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\ActivityResource\Pages\CreateActivity;
use App\Filament\Resources\ActivityResource\Pages\EditActivity;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Coordinator;
use App\Models\Neighbourhood;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Task;
use Filament\Actions\DeleteAction;
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

    $savedActivity = Activity::first();
    $this->assertDatabaseHas('activity_neighbourhood', [
        'activity_id' => $savedActivity->getKey(),
        'neighbourhood_id' => $neighbourhood->getKey(),
    ]);
    $this->assertDatabaseHas('activity_coordinator', [
        'activity_id' => $savedActivity->getKey(),
        'coordinator_id' => $coordinator->getKey(),
    ]);
    $this->assertDatabaseHas('activity_partner', [
        'activity_id' => $savedActivity->getKey(),
        'partner_id' => $partner->getKey(),
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

it('can edit Activity', function () {
    $activity = Activity::factory()->create();
    $activity->project()->associate(Project::factory()->create());
    $activity->task()->associate(Task::factory()->create());
    $activity->contactPerson()->associate(ContactPerson::factory()->create());
    $activity->neighbourhoods()->attach(Neighbourhood::factory(4)->create());
    $activity->coordinators()->attach(Coordinator::factory(2)->create());
    $activity->partners()->attach(Partner::factory(2)->create());
    $activity->save();

    $newActivity = Activity::factory()->make();
    $newProject = Project::factory()->create();
    $newTask = Task::factory()->create();
    $newContactPerson = ContactPerson::factory()->create();
    $newNeighbourhood = Neighbourhood::factory()->create();
    $newNeighbourhoods = $activity->neighbourhoods->add($newNeighbourhood);
    $newCoordinator = Coordinator::factory()->create();
    $newCoordinators = $activity->coordinators->add($newCoordinator);
    $newPartner = Partner::factory()->create();
    $newPartners = $activity->partners->add($newPartner);

    livewire(EditActivity::class, [
        'record' => $activity->getKey()
    ])
        ->fillForm([
            'name' => $newActivity->name,
            'project_id' => $newProject->getKey(),
            'task_id' => $newTask->getKey(),
            'date' => $newActivity->date,
            'contact_person_id' => $newContactPerson->getKey(),
            'neighbourhood_id' => $newNeighbourhoods->map(fn(Neighbourhood $neighbourhood) => $neighbourhood->getKey())->toArray(),
            'coordinator_id' => $newCoordinators->map(fn(Coordinator $coordinator) => $coordinator->getKey())->toArray(),
            'partners_id' => $newPartners->map(fn(Partner $partner) => $partner->getKey())->toArray(),
            'comment' => $newActivity->comment,
        ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Activity::class, [
        'name' => $newActivity->name,
        'project_id' => $newProject->getKey(),
        'task_id' => $newTask->getKey(),
        'date' => $newActivity->date,
        'contact_person_id' => $newContactPerson->getKey(),
        'comment' => $newActivity->comment,
    ]);

    $this->assertDatabaseHas('activity_neighbourhood', [
        'activity_id' => $activity->getKey(),
        'neighbourhood_id' => $newNeighbourhood->getKey(),
    ]);
    $this->assertDatabaseHas('activity_coordinator', [
        'activity_id' => $activity->getKey(),
        'coordinator_id' => $newCoordinator->getKey(),
    ]);
    $this->assertDatabaseHas('activity_partner', [
        'activity_id' => $activity->getKey(),
        'partner_id' => $newPartner->getKey(),
    ]);
});

it('can delete Activity', function () {
    $activity = Activity::factory()->create();
    livewire(EditActivity::class, [
        'record' => $activity->getRouteKey()
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($activity);
});
