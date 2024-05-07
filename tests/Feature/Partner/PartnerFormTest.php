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
