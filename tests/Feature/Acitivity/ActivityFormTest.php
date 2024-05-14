<?php

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
use Filament\Forms\Components\Repeater;
use function Pest\Livewire\livewire;

/** Render */
it('can render Activity create form', function () {
    $this->get(ActivityResource::getUrl('create'))->assertSuccessful();
});

it('can render Activity edit form', function () {
    $activity = Activity::factory()->create();
    $this->get(ActivityResource::getUrl('edit', ['record' => $activity->getKey()]))->assertSuccessful();
});

/** Create */
it('can create Activity', function () {
    $undoRepeaterFake = Repeater::fake();

    $this->withoutExceptionHandling();
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
            'neighbourhood_id' => [$neighbourhood->getKey()],
            'coordinator_id' => [$coordinator->getKey()],
            'comment' => $activity->comment,
            'activityContactPersonPartner' => [
                [
                    'partner_id' => $partner->getKey(),
                    'contact_person_id' => $contactPerson->getKey(),
                ]
            ]
        ])->call('create')
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
    $this->assertDatabaseHas('activity_contact_person_partner', [
        'activity_id' => $savedActivity->getKey(),
        'contact_person_id' => $partner->getKey(),
        'partner_id' => $partner->getKey(),
    ]);

    $undoRepeaterFake();
});

/** Validate */
it('can validate Activity form', function () {
    $undoRepeaterFake = Repeater::fake();

    livewire(CreateActivity::class)
        ->fillForm([
            'name' => null,
            'project_id' => null,
            'task_id' => null,
            'date' => null,
            'neighbourhood_id' => [],
            'coordinator_id' => [],
            'activityContactPersonPartner' => [
                [
                    'partner_id' => null,
                    'contact_person_id' => null,
                ]
            ]
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'project_id' => 'required',
            'task_id' => 'required',
            'date' => 'required',
            'neighbourhood_id' => 'required',
            'coordinator_id' => 'required',
            'activityContactPersonPartner.0.partner_id' => 'required',
        ]);

    $undoRepeaterFake();
});

/** Edit */
it('can update Activity', function () {
    $undoRepeaterFake = Repeater::fake();

    $activity = Activity::factory()->create();
    $activity->project()->associate(Project::factory()->create());
    $activity->task()->associate(Task::factory()->create());
    $activity->neighbourhoods()->attach(Neighbourhood::factory(4)->create());
    $activity->coordinators()->attach(Coordinator::factory(2)->create());
    $activity->save();

    $contactPerson = ContactPerson::factory()->create();
    $partner = Partner::factory()->create();
    $activity->activityContactPersonPartner()->create([
        'contact_person_id' => $contactPerson->getKey(),
        'partner_id' => $partner->getKey(),
    ]);

    $newActivity = Activity::factory()->make();
    $newProject = Project::factory()->create();
    $newTask = Task::factory()->create();
    $newNeighbourhood = Neighbourhood::factory()->create();
    $newNeighbourhoods = $activity->neighbourhoods->add($newNeighbourhood);
    $newCoordinator = Coordinator::factory()->create();
    $newCoordinators = $activity->coordinators->add($newCoordinator);
    $newPartner = Partner::factory()->create();
    $newContactPerson = ContactPerson::factory()->create();

    livewire(EditActivity::class, [
        'record' => $activity->getKey()
    ])->fillForm([
        'name' => $newActivity->name,
        'project_id' => $newProject->getKey(),
        'task_id' => $newTask->getKey(),
        'date' => $newActivity->date,
        'neighbourhood_id' => $newNeighbourhoods->map(fn(Neighbourhood $neighbourhood) => $neighbourhood->getKey())->toArray(),
        'coordinator_id' => $newCoordinators->map(fn(Coordinator $coordinator) => $coordinator->getKey())->toArray(),
        'comment' => $newActivity->comment,
        'activityContactPersonPartner' => [
            [
                'partner_id' => $newPartner->getKey(),
                'contact_person_id' => $newContactPerson->getKey(),
            ]
        ]
    ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Activity::class, [
        'name' => $newActivity->name,
        'project_id' => $newProject->getKey(),
        'task_id' => $newTask->getKey(),
        'date' => $newActivity->date,
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

    $this->assertDatabaseHas('activity_contact_person_partner', [
        'activity_id' => $activity->getKey(),
        'contact_person_id' => $newContactPerson->getKey(),
        'partner_id' => $newPartner->getKey(),
    ]);

    $undoRepeaterFake();
});

/** Delete */
it('can delete Activity', function () {
    $activity = Activity::factory()->create();
    livewire(EditActivity::class, [
        'record' => $activity->getRouteKey()
    ])->callAction(DeleteAction::class);

    $this->assertModelMissing($activity);
});

/** Other */
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
            'comment' => $activity->comment,
            'activityContactPersonPartner' => [
                [
                    'partner_id' => null,
                    'contact_person_id' => null,
                ]
            ],
        ])->assertFormFieldIsDisabled('activityContactPersonPartner.0.contact_person_id');
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
            'comment' => $activity->comment,
            'activityContactPersonPartner' => [
                [
                    'partner_id' => $partner->getKey(),
                    'contact_person_id' => null,
                ]
            ],
        ])->assertFormFieldIsEnabled('activityContactPersonPartner.0.contact_person_id');
});
