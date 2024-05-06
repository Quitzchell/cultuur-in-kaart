<?php

use App\Filament\Resources\TaskResource;
use App\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Models\Task;
use function Pest\Livewire\livewire;

it('can render Task list', function () {
    $this->get(TaskResource::getUrl())->assertSuccessful();
});

it('can List Tasks', function () {
    $task = Task::factory(10)->create();

    livewire(ListTasks::class)
        ->assertCanSeeTableRecords($task)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name');
});
