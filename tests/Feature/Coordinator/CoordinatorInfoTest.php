<?php

use App\Filament\Resources\CoordinatorResource;
use App\Models\Coordinator;

/** Render */
it('can render Coordinator information', function () {
    $coordinator = Coordinator::factory()->create();
    $this->get(CoordinatorResource::getUrl('view', ['record' => $coordinator->getKey()]))->assertSuccessful();
});
