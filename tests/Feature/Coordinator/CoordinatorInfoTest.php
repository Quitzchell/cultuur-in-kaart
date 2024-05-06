<?php

use App\Filament\Resources\CoordinatorResource;
use App\Models\Coordinator;

it('can render coordinator person info', function () {
    $coordinator = Coordinator::factory()->create();

    $this->get(CoordinatorResource::getUrl('view', ['record' => $coordinator->getKey()]))->assertSuccessful();
});
