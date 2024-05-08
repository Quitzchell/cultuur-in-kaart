<?php

use App\Filament\Resources\NeighbourhoodResource;
use App\Models\Neighbourhood;
use function Pest\Livewire\livewire;

/** Render */
it('can render Neighbourhood list', function () {
    $this->get(NeighbourhoodResource::getUrl())->assertSuccessful();
});

it('can List Neighbourhoods', function () {
    $neighbourhood = Neighbourhood::factory(10)->create();

    livewire(NeighbourhoodResource\Pages\ListNeighbourhoods::class)
        ->assertCanSeeTableRecords($neighbourhood)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name');
});
