<?php

use App\Filament\Resources\ActivityResource\Pages\ViewActivities;
use App\Filament\Resources\ActivityResource\RelationManagers\RelatedActivityRelationManager;
use App\Models\Activity;
use App\Models\Project;
use function Pest\Livewire\livewire;

it('can render RelatedActivities on Activities', function () {
    $activities = Activity::factory(5)
        ->create(['project_id' => Project::factory()->create()->getKey()]);

    livewire(RelatedActivityRelationManager::class, [
        'ownerRecord' => $activities->first(),
        'pageClass' => ViewActivities::class
    ])->assertSuccessful();

});
