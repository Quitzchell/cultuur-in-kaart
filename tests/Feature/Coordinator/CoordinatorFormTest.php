<?php

use App\Filament\Resources\ContactPersonResource;
use App\Filament\Resources\CoordinatorResource\Pages\CreateCoordinator;
use App\Models\Coordinator;
use App\Models\Neighbourhood;
use function Pest\Livewire\livewire;

it('can render coordinator form', function () {
    $coordinator = Coordinator::factory()->create();
    $this->get(ContactPersonResource::getUrl('create', ['record' => $coordinator]))->assertSuccessful();
});

it('can create coordinator', function () {
    $neighbourhoods = Neighbourhood::factory(2)->create();
    $coordinator = Coordinator::factory()->make();

    livewire(CreateCoordinator::class)
        ->fillForm([
            'name' => $coordinator->name,
            'email' => $coordinator->email,
            'phone' => $coordinator->phone,
            'role' => $coordinator->role,
            'password' => $coordinator->password,
            'neighborhood_id' => $neighbourhoods->map(fn($neighbourhood) => $neighbourhood->id),
            'workdays' => $coordinator->workdays,
        ])
        ->call('create')
        ->assertHasNoErrors();
});

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
