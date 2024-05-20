<?php

use App\Filament\Resources\DisciplineResource;
use App\Filament\Resources\DisciplineResource\Pages\ListDisciplines;
use App\Models\Discipline;

use function Pest\Livewire\livewire;

/** Render */
it('can render Discipline list', function () {
    $this->get(DisciplineResource::getUrl())->assertSuccessful();
});

it('can list Disciplines', function () {
    $discipline = Discipline::factory(10)->create();

    livewire(ListDisciplines::class)
        ->assertCanSeeTableRecords($discipline)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name');
});
