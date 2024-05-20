<?php

use App\Filament\Resources\PartnerResource;
use App\Filament\Resources\PartnerResource\Pages\CreatePartner;
use App\Models\ContactPerson;
use App\Models\Neighbourhood;
use App\Models\Partner;
use function Pest\Livewire\livewire;

/** Render */
it('can render Partner create form', function () {
    $this->get(PartnerResource::getUrl('create'))->assertSuccessful();
});

it('can render Partner edit form', function () {
    $partner = Partner::factory()->create();
    $this->get(PartnerResource::getUrl('edit', ['record' => $partner->getKey()]))->assertSuccessful();
});

/** Create */
it('can create a Partner', function () {
    $contactPeople = ContactPerson::factory(10)->create();
    $neighbourhood = Neighbourhood::factory()->create();
    $partner = Partner::factory()->make();

    livewire(CreatePartner::class)
        ->fillForm([
            'name' => $partner->name,
            'street' => $partner->street,
            'house_number' => $partner->house_number,
            'house_number_addition' => $partner->house_number_addition,
            'zip' => $partner->zip,
            'city' => $partner->city,
            'neighbourhood_id' => $neighbourhood->getKey(),
            'contact_person_id' => $contactPeople->map(fn(ContactPerson $contactPerson) => $contactPerson->getKey())->toArray(),
            'primary_contact_person_id' => $contactPeople->first()->getKey(),
        ])->call('create')
        ->assertHasNoFormErrors();
});

/** Validate */
it('can validate create Partner form', function () {
    livewire(CreatePartner::class)
        ->fillForm([
            'name' => null,
            'street' => null,
            'house_number' => null,
            'zip' => null,
            'city' => null,
        ])->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'street' => 'required',
            'house_number' => 'required',
            'zip' => 'required',
            'city' => 'required',
        ]);
});

it('can validate numeric on create Partner form', function () {
    livewire(CreatePartner::class)
        ->fillForm([
            'house_number' => 'asdf',
        ])->call('create')
        ->assertHasFormErrors([
            'house_number' => 'numeric',
        ]);
});

/** Edit */
it('can edit a Partner', function () {
    $partner = Partner::factory()->create();
    $neighbourhood = Neighbourhood::factory()->create();
    $contactPeople = ContactPerson::factory(10)->create();
    $partner->contactPeople()->attach($contactPeople);
    $partner->save();
    $partner->contactPerson()->associate($contactPeople->first());
    $partner->neighbourhood()->associate($neighbourhood);

    $newPartner = Partner::factory()->make();
    $newNeighbourhood = Neighbourhood::factory()->create();
    $newContactPerson = ContactPerson::factory()->create();
    $newContactPeople = $partner->contactPeople->add($newContactPerson);

    livewire(PartnerResource\Pages\EditPartner::class, [
        'record' => $partner->getKey(),
    ])
        ->fillForm([
            'name' => $newPartner->name,
            'street' => $newPartner->street,
            'house_number' => $newPartner->house_number,
            'house_number_addition' => $newPartner->house_number_addition,
            'zip' => $newPartner->zip,
            'city' => $newPartner->city,
            'neighbourhood_id' => $newNeighbourhood->getKey(),
            'contact_person_id' => $newContactPeople->map(fn(ContactPerson $contactPerson) => $contactPerson->getKey())->toArray(),
            'primary_contact_person_id' => $newContactPerson->getKey(),
        ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Partner::class, [
        'name' => $newPartner->name,
        'street' => $newPartner->street,
        'house_number' => $newPartner->house_number,
        'house_number_addition' => $newPartner->house_number_addition,
        'zip' => $newPartner->zip,
        'city' => $newPartner->city,
        'neighbourhood_id' => $newNeighbourhood->getKey(),
    ]);

    $this->assertDatabaseHas('contact_person_partner', [
        'partner_id' => $partner->getKey(),
        'contact_person_id' => $newContactPerson->getKey(),
    ]);
});
