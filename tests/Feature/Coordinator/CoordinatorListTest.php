<?php

use App\Filament\Resources\CoordinatorResource;
use App\Filament\Resources\CoordinatorResource\Pages\ListCoordinators;
use App\Models\Coordinator;
use function Pest\Livewire\livewire;

/** Render */
it('can render Coordinator list', function () {
    $this->get(CoordinatorResource::getUrl())->assertSuccessful();
});

it('can list Coordinators', function () {
    Coordinator::factory(9)->create();
    $coordinators = Coordinator::all();

    livewire(ListCoordinators::class)
        ->assertCanSeeTableRecords($coordinators)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('role')
        ->assertTableColumnExists('role')
        ->assertCanRenderTableColumn('phone')
        ->assertTableColumnExists('phone');
});
