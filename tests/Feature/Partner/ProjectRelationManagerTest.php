<?php

use App\Filament\Resources\PartnerResource\Pages\ViewPartner;
use App\Filament\Resources\PartnerResource\RelationManagers\ProjectRelationManager;
use App\Models\Activity;
use App\Models\Partner;
use App\Models\Project;
use function Pest\Livewire\livewire;

it('can render related Projects', function () {
    $partner = Partner::factory()->create();
    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class
    ])->assertSuccessful();
});

it('can search related Projects', function () {
    $partner = Partner::factory()->create();

    $projects = Project::factory(3)->create()->each(function (Project $project) use ($partner) {
        $project->activities()->saveMany(Activity::factory(3)->create()->each(function (Activity $activity) use ($partner) {
            $activity->partners()->attach($partner);
        }));
    });

    $name = $projects->first()->name;
    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class
    ])->searchTable($name)
        ->assertCanSeeTableRecords($projects->where('name', $name))
        ->assertCanNotSeeTableRecords($projects->where('name', '!==', $name));
});


