<?php

use App\Filament\Resources\ContactPersonResource;
use App\Filament\Resources\ContactPersonResource\Pages\CreateContactPerson;
use App\Filament\Resources\ContactPersonResource\Pages\EditContactPerson;
use App\Models\ContactPerson;
use App\Models\Partner;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

/** Render */
it('can render ContactPerson form', function () {
    $this->get(ContactPersonResource::getUrl('create'))->assertSuccessful();
});

it('can render ContactPerson edit form', function () {
    $contactPerson = ContactPerson::factory()->create();
    $this->get(ContactPersonResource::getUrl('edit', ['record' => $contactPerson->getKey()]))->assertSuccessful();
});

/** Create */
it('can create ContactPerson', function () {
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

    $savedContactPerson = ContactPerson::first();
    $this->assertDatabaseHas('contact_person_partner', [
        'contact_person_id' => $savedContactPerson->getKey(),
        'partner_id' => $partner->getKey(),
    ]);
});

/** Validate */
it('can validate create ContactPerson form', function () {
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
            'phone' => 'Het telefoonnummer is ongeldig.',
        ]);
});

/** Edit */
it('can edit ContactPerson', function () {
    $contactPerson = ContactPerson::factory()->create();
    $partner = Partner::factory()->create();
    $contactPerson->partners()->attach($partner);

    $newContactPerson = ContactPerson::factory()->make();
    $newPartner = Partner::factory()->create();
    $newPartners = $contactPerson->partners->add($newPartner);

    livewire(editContactPerson::class, [
        'record' => $contactPerson->getKey(),
    ])->fillForm([
        'name' => $newContactPerson->name,
        'email' => $newContactPerson->email,
        'phone' => $newContactPerson->phone,
        'partner_id' => $newPartners->map(fn (Partner $partner) => $partner->getKey())->toArray(),
        'comment' => $newContactPerson->comment,
    ])->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas(ContactPerson::class, [
        'name' => $newContactPerson->name,
        'email' => $newContactPerson->email,
        'phone' => $newContactPerson->phone,
        'comment' => $newContactPerson->comment,
    ]);

    $this->assertDatabaseHas('contact_person_partner', [
        'contact_person_id' => $contactPerson->getKey(),
        'partner_id' => $partner->getKey(),
    ]);
});

/** Delete */
it('can delete ContactPerson', function () {
    $contactPerson = ContactPerson::factory()->create();
    livewire(EditContactPerson::class, [
        'record' => $contactPerson->getRouteKey(),
    ])->callAction(DeleteAction::class);

    $this->assertModelMissing($contactPerson);
});
