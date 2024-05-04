<?php

use App\Filament\Resources\ContactPersonResource;
use App\Models\ContactPerson;

it('can render contact person info', function () {
    $contactPerson = ContactPerson::factory()->create();

    $this->get(ContactPersonResource::getUrl('view', ['record' => $contactPerson->getKey()]))->assertSuccessful();
});
