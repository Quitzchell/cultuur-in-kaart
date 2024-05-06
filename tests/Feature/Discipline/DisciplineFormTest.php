<?php

use App\Filament\Resources\DisciplineResource;
use App\Filament\Resources\DisciplineResource\Pages\CreateDiscipline;
use App\Models\Discipline;
use function Pest\Livewire\livewire;

/** Rendering */
it('can render discipline form', function () {
    $discipline = Discipline::factory()->create();
    $this->get(DisciplineResource::getUrl('create', ['record' => $discipline->getKey()]))->assertSuccessful();
});

it('can create discipline', function () {
    $discipline = Discipline::factory()->make();

    livewire(CreateDiscipline::class)
        ->fillForm([
            'name' => $discipline->name,
        ])->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas(Discipline::class, [
        'name' => $discipline->name,
    ]);
});
