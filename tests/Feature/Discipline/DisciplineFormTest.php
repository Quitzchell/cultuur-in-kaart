<?php

use App\Filament\Resources\DisciplineResource;
use App\Filament\Resources\DisciplineResource\Pages\CreateDiscipline;
use App\Models\Discipline;
use function Pest\Livewire\livewire;

/** Rendering */
it('can render discipline create form', function () {
    $discipline = Discipline::factory()->create();
    $this->get(DisciplineResource::getUrl('create', ['record' => $discipline->getKey()]))->assertSuccessful();
});

it('can render discipline edit form', function () {
    $discipline = Discipline::factory()->create();
    $this->get(DisciplineResource::getUrl('edit', ['record' => $discipline->getKey()]))->assertSuccessful();
});

/** Creation */
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

it('can validate discipline form', function () {
    livewire(CreateDiscipline::class)
        ->fillForm([
            'name' => null
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required'
        ]);
});
