<?php

use App\Filament\Resources\ActivityResource;
use App\Models\Activity;
use function Pest\Livewire\livewire;

it('can render Activity List', function () {
    $this->get(ActivityResource::getUrl())->assertSuccessful();
});

it('can list activities', function () {
    $activities = Activity::factory()
        ->count(10)
        ->create();

    livewire(ActivityResource\Pages\ListActivities::class)
        ->assertCanSeeTableRecords($activities);
});
