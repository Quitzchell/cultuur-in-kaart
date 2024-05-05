<?php

use App\Filament\Resources\CoordinatorResource;
use App\Filament\Resources\CoordinatorResource\Pages\ListCoordinator;
use App\Models\Coordinator;
use function Pest\Livewire\livewire;

/** Rendering */
it('can render coordinator list', function () {
    $this->get(CoordinatorResource::getUrl())->assertSuccessful();
});

it('can list Coordinators', function () {
    Coordinator::factory(9)->create();
    $coordinators = Coordinator::all();

    livewire(ListCoordinator::class)
        ->set('coordinators', $coordinators)
        ->assertCanSeeTableRecords($coordinators)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('role')
        ->assertTableColumnExists('role')
        ->assertCanRenderTableColumn('phone')
        ->assertTableColumnExists('phone');
});
