<?php

use App\Filament\Resources\ContactPersonResource;
use App\Filament\Resources\ContactPersonResource\Pages\ListContactPeople;
use App\Models\ContactPerson;
use App\Models\Partner;
use function Pest\Livewire\livewire;

/** Rendering */
it('can render contact person list', function () {
    $this->get(ContactPersonResource::getUrl())->assertSuccessful();
});

it('can list contact people', function () {
    $contactPerson = ContactPerson::factory(10)->create();

    livewire(ListContactPeople::class)
        ->assertCanSeeTableRecords($contactPerson)
        ->assertCountTableRecords(10)
        ->assertCanRenderTableColumn('name')
        ->assertTableColumnExists('name')
        ->assertCanRenderTableColumn('email')
        ->assertTableColumnExists('email')
        ->assertCanRenderTableColumn('phone')
        ->assertTableColumnExists('phone')
        ->assertCanRenderTableColumn('partners.name')
        ->assertTableColumnExists('partners.name');
});

/** Searching */
it('can search contact people by name', function () {
    $contactPeople = ContactPerson::factory(10)->create();
    $name = $contactPeople->first()->name;

    livewire(ListContactPeople::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($contactPeople->where('name', $name))
        ->assertCanNotSeeTableRecords($contactPeople->where('name', '!==', $name));
});

it('can search contact people by partner', function () {
    $partners = Partner::factory(10)->create();
    $contactPeople = ContactPerson::factory(10)->create()->each(function ($contactPerson) use ($partners) {
        $contactPerson->partners()->attach($partners->random());
    });
    $partner = $contactPeople->first()->partners->first();
    $filteredContactPeople = $contactPeople->filter(function ($contactPerson) use ($partner) {
        return $contactPerson->partners->contains($partner);
    });

    livewire(ListContactPeople::class)
        ->searchTable($partner->name)
        ->assertCanSeeTableRecords($filteredContactPeople)
        ->assertCanNotSeeTableRecords($contactPeople->diff($filteredContactPeople));
});

/** Filtering */
it('can filter contact people by partner', function () {
    $partners = Partner::factory(10)->create();
    $contactPeople = ContactPerson::factory(10)->create()->each(function ($contactPerson) use ($partners) {
        $contactPerson->partners()->attach($partners->random());
    });
    $partner = $contactPeople->first()->partners->first();
    $filteredContactPeople = $contactPeople->filter(function ($contactPerson) use ($partner) {
        return $contactPerson->partners->contains($partner);
    });

    livewire(ListContactPeople::class)
        ->assertCanSeeTableRecords($contactPeople)
        ->filterTable('partner_id', $partner->getKey())
        ->assertCanSeeTableRecords($filteredContactPeople)
        ->assertCanNotSeeTableRecords($contactPeople->diff($filteredContactPeople));
});


