<?php

use App\Filament\Resources\CoordinatorResource;
use App\Models\Coordinator;

it('can render coordinator Person information', function () {
    $coordinator = Coordinator::factory()->create();
    $this->get(CoordinatorResource::getUrl('view', ['record' => $coordinator->getKey()]))->assertSuccessful();
});
