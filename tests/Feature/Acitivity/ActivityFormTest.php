<?php

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\ActivityResource\Pages\CreateActivity;
use App\Filament\Resources\ActivityResource\Pages\EditActivity;
use App\Models\Activity;

//use App\Models\ContactPerson;
use App\Models\Coordinator;
use App\Models\Discipline;
use App\Models\Neighbourhood;

//use App\Models\Partner;
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

    $project = Project::factory()->create();
    $task = Task::factory()->create();
    $discipline = Discipline::factory()->create();
    $neighbourhood = Neighbourhood::factory()->create();
    $coordinators = coordinator::factory(2)->create();
    $activity = Activity::factory()->make();
    $partner = Partner::factory()->create();

    livewire(CreateActivity::class)
        ->fillForm([
            'name' => $activity->name,
            'date' => $activity->date,
            'comment' => $activity->comment,
            'task_id' => $task->getKey(),
            'project_id' => $project->getKey(),
            'neighbourhood_id' => $neighbourhood->getKey(),
            'discipline_id' => $discipline->getKey(),
            'coordinators_id' => $coordinators->pluck('id')->toArray(),
            'activityPartner' => [
                [
                    'partner_id' => $partner->getKey(),
                ]
            ]
        ])->call('create')->assertHasNoFormErrors();


    $this->assertDatabaseHas(Activity::class, [
        'name' => $activity->name,
        'project_id' => $project->getKey(),
        'task_id' => $task->getKey(),
        'date' => $activity->date,
        'comment' => $activity->comment,
        'neighbourhood_id' => $neighbourhood->getKey(),
    ]);

    $savedActivity = Activity::first();
    $this->assertDatabaseHas('activity_coordinator', [
        'activity_id' => $savedActivity->getKey(),
        'coordinator_id' => $coordinators->first()->getKey(),
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
            'neighbourhood_id' => null,
            'coordinators_id' => [],
            'activityPartner' => [
                [
                    'partner_id' => null,
//                    'contact_person_id' => null,
                ]
            ]
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'project_id' => 'required',
            'task_id' => 'required',
            'date' => 'required',
            'neighbourhood_id' => 'required',
            'coordinators_id' => 'required',
            'activityPartner.0.partner_id' => 'required',
        ]);

    $undoRepeaterFake();
});

/** Edit */
it('can update Activity', function () {
    $undoRepeaterFake = Repeater::fake();

    $activity = Activity::factory()->create();
    $activity->project()->associate(Project::factory()->create());
    $activity->task()->associate(Task::factory()->create());
    $activity->neighbourhood()->associate(Neighbourhood::factory()->create());
    $activity->coordinators()->attach(Coordinator::factory(2)->create());
    $activity->save();

//    $contactPerson = ContactPerson::factory()->create();
    $partner = Partner::factory()->create();
    $activity->activityPartner()->create([
        'partner_id' => $partner->getKey(),
//        'contact_person_id' => $contactPerson->getKey(),
    ]);

    $newActivity = Activity::factory()->make();
    $newProject = Project::factory()->create();
    $newTask = Task::factory()->create();
    $newNeighbourhood = Neighbourhood::factory()->create();
    $newCoordinator = Coordinator::factory()->create();
    $newCoordinators = $activity->coordinators->add($newCoordinator);
    $newPartner = Partner::factory()->create();
//    $newContactPerson = ContactPerson::factory()->create();

    livewire(EditActivity::class, [
        'record' => $activity->getKey()
    ])->fillForm([
        'name' => $newActivity->name,
        'project_id' => $newProject->getKey(),
        'task_id' => $newTask->getKey(),
        'date' => $newActivity->date,
        'neighbourhood_id' => $newNeighbourhood->getKey(),
        'coordinators_id' => $newCoordinators->pluck('id')->toArray(),
        'comment' => $newActivity->comment,
        'activityPartner' => [
            [
                'partner_id' => $newPartner->getKey(),
//                'contact_person_id' => $newContactPerson->getKey(),
            ]
        ]
    ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Activity::class, [
        'name' => $newActivity->name,
        'project_id' => $newProject->getKey(),
        'task_id' => $newTask->getKey(),
        'neighbourhood_id' => $newNeighbourhood->getKey(),
        'date' => $newActivity->date,
        'comment' => $newActivity->comment,
    ]);

    $this->assertDatabaseHas('activity_coordinator', [
        'activity_id' => $activity->getKey(),
        'coordinator_id' => $newCoordinator->getKey(),
    ]);

    $this->assertDatabaseHas('activity_partner', [
        'activity_id' => $activity->getKey(),
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

///** Other */
//it('can disable contact_person_id field on Activity', function () {
//    $project = Project::factory()->create();
//    $task = Task::factory()->create();
//    $neighbourhood = Neighbourhood::factory()->create();
//    $coordinator = Coordinator::factory()->create();
//    $activity = Activity::factory()->make();
//
//    livewire(CreateActivity::class)
//        ->fillForm([
//            'name' => $activity->name,
//            'project_id' => $project->getKey(),
//            'task_id' => $task->getKey(),
//            'date' => $activity->date,
//            'neighbourhood_id' => $neighbourhood->getKey(),
//            'coordinator_id' => [$coordinator->getKey()],
//            'comment' => $activity->comment,
//            'contactPersonPartner' => [
//                [
//                    'partner_id' => null,
//                    'contact_person_id' => null,
//                ]
//            ],
//        ])->assertFormFieldIsDisabled('contactPersonPartner.0.contact_person_id');
//});
//
//it('can enable contact_person_id field on Activity', function () {
//    $project = Project::factory()->create();
//    $task = Task::factory()->create();
//    $neighbourhood = Neighbourhood::factory()->create();
//    $coordinator = Coordinator::factory()->create();
//    $partner = Partner::factory()->create();
//    $activity = Activity::factory()->make();
//
//    livewire(CreateActivity::class)
//        ->fillForm([
//            'name' => $activity->name,
//            'project_id' => $project->getKey(),
//            'task_id' => $task->getKey(),
//            'date' => $activity->date,
//            'neighbourhood_id' => $neighbourhood->getKey(),
//            'coordinator_id' => [$coordinator->getKey()],
//            'comment' => $activity->comment,
//            'contactPersonPartner' => [
//                [
//                    'partner_id' => $partner->getKey(),
//                    'contact_person_id' => null,
//                ]
//            ],
//        ])->assertFormFieldIsEnabled('contactPersonPartner.0.contact_person_id');
//});
