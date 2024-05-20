<?php

use App\Filament\RelationManagers\PartnerResource\ProjectRelationManager;
use App\Filament\Resources\PartnerResource\Pages\ViewPartner;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use App\Models\Project;
use function Pest\Livewire\livewire;

/** Render */
it('can render related Projects', function () {
    $partner = Partner::factory()->create();
    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class
    ])->assertSuccessful();
});

it('can list related Projects', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $projects = Project::factory(2)->create();
    $activities = Activity::factory(10)->create();

    foreach ($activities as $key => $activity) {
        $activity->activityPartnerContactPerson()->create([
            'contact_person_id' => $contactPerson->id,
            'partner_id' => $partner->id,
        ]);
        $activity->partners()->attach($partner);
        $activity->project()->associate($projects[$key % 2]);
        $activity->save();
    }

    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class
    ])->assertCanSeeTableRecords($projects);
});

/** Search */
it('can search related Projects', function () {
    $partner = Partner::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $projects = Project::factory(2)->create();
    $activities = Activity::factory(10)->create();

    foreach ($activities as $key => $activity) {
        $activity->activityPartnerContactPerson()->create([
            'contact_person_id' => $contactPerson->id,
            'partner_id' => $partner->id,
        ]);
        $activity->partners()->attach($partner);
        $activity->project()->associate($projects[$key % 2]);
        $activity->save();
    }

    $name = $projects->first()->name;
    livewire(ProjectRelationManager::class, [
        'ownerRecord' => $partner,
        'pageClass' => ViewPartner::class
    ])->searchTable($name)
        ->assertCanSeeTableRecords($projects->where('name', $name))
        ->assertCanNotSeeTableRecords($projects->where('name', '!==', $name));
});


