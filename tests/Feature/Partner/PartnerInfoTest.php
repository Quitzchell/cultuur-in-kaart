<?php

use App\Filament\Resources\PartnerResource;
use App\Models\Partner;

/** Render */
it('can render Partner information', function () {
    $partner = Partner::factory()->create();
    $this->get(PartnerResource::getUrl('view', ['record' => $partner->getKey()]))->assertSuccessful();
});
