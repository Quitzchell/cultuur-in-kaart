<?php

use App\Filament\Resources\ContactPersonResource;
use App\Filament\Resources\ContactPersonResource\Pages\CreateContactPerson;
use App\Models\ContactPerson;
use App\Models\Partner;
use function Pest\Livewire\livewire;

it('can render contact person form', function () {
    $contactPerson = ContactPerson::factory()->create();
    $this->get(ContactPersonResource::getUrl('create', ['record' => $contactPerson->getKey()]))->assertSuccessful();
});

it('can create contact person', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->make();

    livewire(CreateContactPerson::class)
        ->fillForm([
            'name' => $contactPerson->name,
            'email' => $contactPerson->email,
            'phone' => $contactPerson->phone,
            'partner_id' => [$partner->getKey()],
            'comment' => $contactPerson->comment,
        ])
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas(ContactPerson::class, [
        'name' => $contactPerson->name,
        'email' => $contactPerson->email,
        'phone' => $contactPerson->phone,
        'comment' => $contactPerson->comment,
    ]);
});

it('can validate create contact person form', function () {
    livewire(CreateContactPerson::class)
        ->fillForm([
            'name' => null,
            'email' => null,
            'phone' => 'asdasdfasdfasdf',
            'partner_id' => [],
            'comment' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'phone' => 'Het telefoonnummer is ongeldig.'
        ]);
});

it('can render contact person edit form', function () {
    $contactPerson = ContactPerson::factory()->create();
    $this->get(ContactPersonResource::getUrl('edit', ['record' => $contactPerson->getKey()]))->assertSuccessful();
});
