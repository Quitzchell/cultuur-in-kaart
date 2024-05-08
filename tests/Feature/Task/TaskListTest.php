<?php

use App\Filament\Resources\TaskResource;
use App\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Models\Task;
use function Pest\Livewire\livewire;

/** Render */
it('can render Task list', function () {
    $this->get(TaskResource::getUrl())->assertSuccessful();
});

it('can List Tasks', function () {
    $tasks = Task::factory(10)->create();

    livewire(ListTasks::class)
        ->assertCanSeeTableRecords($tasks)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name');
});
