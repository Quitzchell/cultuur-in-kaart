<?php

use App\Filament\Resources\DisciplineResource;
use App\Filament\Resources\DisciplineResource\Pages\CreateDiscipline;
use App\Filament\Resources\DisciplineResource\Pages\EditDiscipline;
use App\Models\Discipline;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

/** Render */
it('can render Discipline create form', function () {
    $this->get(DisciplineResource::getUrl('create'))->assertSuccessful();
});

it('can render Discipline edit form', function () {
    $discipline = Discipline::factory()->create();
    $this->get(DisciplineResource::getUrl('edit', ['record' => $discipline->getKey()]))->assertSuccessful();
});

/** Create */
it('can create Discipline', function () {
    $discipline = Discipline::factory()->make();

    livewire(CreateDiscipline::class)
        ->fillForm([
            'name' => $discipline->name,
        ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Discipline::class, [
        'name' => $discipline->name,
    ]);
});

/** Validate */
it('can validate Discipline form', function () {
    livewire(CreateDiscipline::class)
        ->fillForm([
            'name' => null,
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ]);
});

/** Edit */
it('can update Discipline', function () {
    $discipline = Discipline::factory()->create();
    $newDiscipline = Discipline::factory()->make();

    livewire(EditDiscipline::class, [
        'record' => $discipline->getKey(),
    ])->fillForm([
        'name' => $newDiscipline->name,
    ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Discipline::class, [
        'name' => $newDiscipline->name,
    ]);
});

/** Delete */
it('can delete Discipline', function () {
    $discipline = Discipline::factory()->create();
    livewire(EditDiscipline::class, [
        'record' => $discipline->getKey(),
    ])->callAction(DeleteAction::class);

    $this->assertModelMissing($discipline);
});
