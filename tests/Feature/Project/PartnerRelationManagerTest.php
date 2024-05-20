<?php

use App\Filament\RelationManagers\ProjectResource\PartnerRelationManager;
use App\Filament\Resources\ProjectResource\Pages\ViewProject;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

it('can render related Partners', function () {
    $project = Project::factory()->create();
    livewire(PartnerRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
    ])->assertSuccessful();
});

it('can list related Partners', function () {
    $project = Project::factory()->create();
    $activities = Activity::factory(10)->create(['project_id' => $project->id]);
    $partners = Partner::factory(2)->create();
    $contactPerson = ContactPerson::factory()->create();
    foreach ($partners as $partner) {
        $partner->contactPerson()->associate($contactPerson->getKey());
        $partner->save();
    }

    foreach ($activities as $activity) {
        $activity->partners()->attach($partners->random());
    }

    livewire(PartnerRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
    ])->assertCanSeeTableRecords($partners);
});

it('can search related Partners by name', function () {
    $project = Project::factory()->create();
    $activities = Activity::factory(100)->create(['project_id' => $project->id]);
    $partners = Partner::factory(2)->state(new Sequence(['name' => 'Fabriek Magnifiek'], ['name' => 'Visserij Rijstenbrij']))->create();
    foreach ($activities as $key => $activity) {
        $activity->partners()->attach($partners[$key % 2]);
    }

    $name = $partners->first()->name;
    livewire(PartnerRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
    ])->searchTable($name)
        ->assertCanSeeTableRecords($partners->where('name', $name))
        ->assertCanNotSeeTableRecords($partners->where('name', '!==', $name));
});

it('can search related Partners by contact person', function () {
    $project = Project::factory()->create();
    $activities = Activity::factory(100)->create(['project_id' => $project->id]);
    $partners = Partner::factory(2)->state(new Sequence(['name' => 'Fabriek Magnifiek'], ['name' => 'Visserij Rijstenbrij']))->create();
    $contactPersons = ContactPerson::factory(2)->create();
    foreach ($partners as $key => $partner) {
        $partner->contactPerson()->associate($contactPersons[$key % 2]->getKey());
        $partner->save();
    }

    foreach ($activities as $key => $activity) {
        $activity->partners()->attach($partners[$key % 2]);
    }

    $contactPerson = $partners->first()->contactPerson;
    $filteredPartners = $partners->filter(function (Partner $partner) use ($contactPerson) {
        return $partner->contactPerson->name === $contactPerson->name;
    });

    livewire(PartnerRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
    ])->searchTable($contactPerson->name)
        ->assertCanSeeTableRecords($filteredPartners)
        ->assertCanNotSeeTableRecords($partners->diff($filteredPartners));
});

it('can filter related Partners by neighbourhood', function () {
    $project = Project::factory()->create();
    $activity = Activity::factory()->create();
    $activity->project()->associate($project);
    $activity->save();
    $partners = Partner::factory()->count(10)->create()->each(function (Partner $partner) use ($activity) {
        $partner->activities()->attach($activity);
        $partner->save();
    });

    $partner = $partners->first();
    livewire(PartnerRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => ViewProject::class,
    ])->assertCanSeeTableRecords($partners)
        ->filterTable('neighbourhood', $partner->neighbourhood)
        ->assertCanSeeTableRecords($partners->where('neighbourhood', $partner->neighbourhood))
        ->assertCanNotSeeTableRecords($partners->where('neighbourhood', '!==', $partner->neighbourhood));
});
