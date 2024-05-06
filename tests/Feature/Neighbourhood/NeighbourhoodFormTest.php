<?php

use App\Filament\Resources\NeighbourhoodResource;
use App\Filament\Resources\NeighbourhoodResource\Pages\CreateNeighbourhood;
use App\Filament\Resources\NeighbourhoodResource\Pages\EditNeighbourhood;
use App\Models\Neighbourhood;
use function Pest\Livewire\livewire;

/** Render */
it('can render create Neighbourhood form', function () {
    $this->get(NeighbourhoodResource::getUrl('create'))->assertSuccessful();
});

it('can render edit Neighbourhood form', function () {
    $neighbourhood = Neighbourhood::factory()->create();
    $this->get(NeighbourhoodResource::getUrl('edit', ['record' => $neighbourhood->getKey()]))->assertSuccessful();
});

/** Create */
it('can create Neighbourhood', function () {
    $neighbourhood = Neighbourhood::factory()->create();

    livewire(CreateNeighbourhood::class)
        ->fillForm([
            'name' => $neighbourhood->name,
        ])->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Neighbourhood::class, [
        'name' => $neighbourhood->name,
    ]);
});

/** Validate */
it('can validate Neighbourhood form', function () {
    livewire(CreateNeighbourhood::class)
        ->fillForm([
            'name' => null,
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required'
        ]);
});

/** Edit */
it('can update Neighbourhood', function () {
    $neighbourhood = Neighbourhood::factory()->create();
    $newNeighbourhood = Neighbourhood::factory()->make();

    livewire(EditNeighbourhood::class, [
        'record' => $neighbourhood->getKey()
    ])->fillForm([
        'name' => $newNeighbourhood->name,
    ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Neighbourhood::class, [
        'name' => $newNeighbourhood->name,
    ]);
});
