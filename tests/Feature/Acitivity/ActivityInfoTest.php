<?php

use App\Filament\Resources\ActivityResource;
use App\Models\Activity;

/** Render */
it('can render Activity info', function () {
    $activity = Activity::factory()->create();
    $this->get(ActivityResource::getUrl('view', ['record' => $activity->getKey()]))->assertSuccessful();
});
