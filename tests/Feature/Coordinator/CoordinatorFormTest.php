<?php

use App\Enums\Workday\Workday;
use App\Filament\Resources\CoordinatorResource;
use App\Filament\Resources\CoordinatorResource\Pages\CreateCoordinator;
use App\Filament\Resources\CoordinatorResource\Pages\EditCoordinator;
use App\Models\Coordinator;
use App\Models\Neighbourhood;

use function Pest\Livewire\livewire;

/** Render */
it('can render Coordinator create form', function () {
    $this->get(CoordinatorResource::getUrl('create'))->assertSuccessful();
});

it('can render Coordinator edit form', function () {
    $coordinator = Coordinator::factory()->create();
    $this->get(CoordinatorResource::getUrl('edit', ['record' => $coordinator]))->assertSuccessful();
});

/** Create */
it('can create a Coordinator', function () {
    $neighbourhoods = Neighbourhood::factory(2)->create();
    $coordinator = Coordinator::factory()->make();

    livewire(CreateCoordinator::class)
        ->fillForm([
            'name' => $coordinator->name,
            'email' => $coordinator->email,
            'phone' => $coordinator->phone,
            'role' => $coordinator->role,
            'password' => $coordinator->password,
            'neighborhood_id' => $neighbourhoods->map(fn (Neighbourhood $neighbourhood) => $neighbourhood->getKey()),
            'workdays' => $coordinator->workdays,
        ])->call('create')
        ->assertHasNoErrors();
});

/** Validate */
it('can validate create coordinator form', function () {
    livewire(CreateCoordinator::class)
        ->fillForm([
            'name' => null,
            'email' => null,
            'phone' => null,
            'role' => null,
            'password' => null,
            'neighborhood_id' => [],
            'workdays' => [],
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);
});

/** Edit */
it('can edit a Coordinator', function () {
    $coordinator = Coordinator::factory()->create();
    $coordinator->neighbourhoods()->attach(Neighbourhood::factory(2)->create());
    $coordinator->save();

    $newCoordinator = Coordinator::factory()->make();
    $newNeighbourhood = Neighbourhood::factory()->create();
    $newNeighbourhoods = $coordinator->neighbourhoods->add($newNeighbourhood);
    $newWorkdays = Workday::labelsToCollection()->random(3);

    livewire(EditCoordinator::class, [
        'record' => $coordinator->getKey(),
    ])
        ->fillForm([
            'name' => $newCoordinator->name,
            'email' => $newCoordinator->email,
            'phone' => $newCoordinator->phone,
            'role' => $newCoordinator->role,
            'neighbourhood_id' => $newNeighbourhoods->map(fn (Neighbourhood $neighbourhood) => $neighbourhood->getKey())->toArray(),
            'workdays' => $newWorkdays,
        ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Coordinator::class, [
        'name' => $newCoordinator->name,
        'email' => $newCoordinator->email,
        'phone' => $newCoordinator->phone,
        'role' => $newCoordinator->role,
        'workdays' => json_encode($newWorkdays),
    ]);

    $this->assertDatabaseHas('coordinator_neighbourhood', [
        'coordinator_id' => $coordinator->getKey(),
        'neighbourhood_id' => $newNeighbourhood->getKey(),
    ]);
});

/** Other */
it('can hide password field on edit coordinator view', function () {
    $coordinator = Coordinator::factory()->create();

    livewire(EditCoordinator::class, [
        'record' => $coordinator->getRouteKey(),
    ])->assertFormFieldIsHidden('password');
});
