<?php

use App\Filament\Resources\TaskResource;
use App\Filament\Resources\TaskResource\Pages\CreateTask;
use App\Filament\Resources\TaskResource\Pages\EditTask;
use App\Models\Task;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

/** Render */
it('can render create Task form', function () {
    $this->get(TaskResource::getUrl('create'))->assertSuccessful();
});

it('can render edit Task form', function () {
    $task = Task::factory()->create();
    $this->get(TaskResource::getUrl('edit', ['record' => $task->getKey()]))->assertSuccessful();
});

/** Create */
it('can create Task', function () {
    $task = Task::factory()->create();

    livewire(CreateTask::class)
        ->fillForm([
            'name' => $task->name,
        ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Task::class, [
        'name' => $task->name,
    ]);
});

/** Validate */
it('can validate Task form', function () {
    livewire(CreateTask::class)
        ->fillForm([
            'name' => null,
            'emoji_unicode' => null,
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ]);
});

/** Edit */
it('can update Task', function () {
    $task = Task::factory()->create();
    $newTask = Task::factory()->make();

    livewire(EditTask::class, [
        'record' => $task->getKey(),
    ])->fillForm([
        'name' => $newTask->name,
    ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Task::class, [
        'name' => $newTask->name,
    ]);
});

/** Delete */
it('can delete Task', function () {
    $task = Task::factory()->create();
    livewire(EditTask::class, [
        'record' => $task->getKey(),
    ])->callAction(DeleteAction::class);

    $this->assertModelMissing($task);
});
