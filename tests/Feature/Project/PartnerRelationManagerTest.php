<?php
//
//use App\Filament\Resources\ProjectResource\Pages\ViewProjects;
//use App\Filament\Resources\ProjectResource\RelationManagers\PartnerRelationManager;
//use App\Models\Activity;
//use App\Models\Partner;
//use App\Models\Project;
//use function Pest\Livewire\livewire;
//
//it('can render related Partners', function () {
//    $project = Project::factory()->create();
//    livewire(PartnerRelationManager::class, [
//        'ownerRecord' => $project,
//        'pageClass' => ViewProjects::class
//    ])->assertSuccessful();
//});
//
//it('can list related Partners', function () {
//    $project = Project::factory()->create();
//    $activities = Activity::factory(10)->create()->each(function (Activity $activity) use ($project) {
//        $activity->project()->associate($project);
//        $activity->save();
//    });
//
//    $partners = Partner::factory()->count(3)->create()->each(function (Partner $partner) use ($activities) {
//        $partner->activities()->attach($activities);
//    });
//
//    livewire(PartnerRelationManager::class, [
//        'ownerRecord' => $project,
//        'pageClass' => ViewProjects::class
//    ])->assertCanSeeTableRecords($partners);
//});
//
//it('can search related Partners by name', function () {
//    $project = Project::factory()->create();
//    $activity = Activity::factory()->create();
//    $activity->project()->associate($project);
//    $activity->save();
//    $partners = Partner::factory()->count(10)->create()->each(function (Partner $partner) use ($activity) {
//        $partner->activities()->attach($activity);
//    });
//
//    $name = $partners->first()->name;
//    livewire(PartnerRelationManager::class, [
//        'ownerRecord' => $project,
//        'pageClass' => ViewProjects::class
//    ])->searchTable($name)
//        ->assertCanSeeTableRecords($partners->where('name', $name))
//        ->assertCanNotSeeTableRecords($partners->where('name', '!==', $name));
//});
//
//it('can search related Partners by contact person', function () {
//    $project = Project::factory()->create();
//    $activity = Activity::factory()->create();
//    $activity->project()->associate($project);
//    $activity->save();
//    $partners = Partner::factory()->count(10)->create()->each(function (Partner $partner) use ($activity) {
//        $partner->activities()->attach($activity);
//    });
//
//    $contactPerson = $partners->first()->primaryContactPerson;
//    $filteredPartners = $partners->filter(function (Partner $partner) use ($contactPerson) {
//        return $partner->primaryContactPerson === $contactPerson;
//    });
//
//    livewire(PartnerRelationManager::class, [
//        'ownerRecord' => $project,
//        'pageClass' => ViewProjects::class
//    ])->searchTable($contactPerson)
//        ->assertCanSeeTableRecords($filteredPartners)
//        ->assertCanNotSeeTableRecords($partners->diff($filteredPartners));
//});
//
//it('can filter related Partners by neighbourhood', function () {
//    $project = Project::factory()->create();
//    $activity = Activity::factory()->create();
//    $activity->project()->associate($project);
//    $activity->save();
//    $partners = Partner::factory()->count(10)->create()->each(function (Partner $partner) use ($activity) {
//        $partner->activities()->attach($activity);
//    });
//
//    $partner = $partners->first();
//    livewire(PartnerRelationManager::class, [
//        'ownerRecord' => $project,
//        'pageClass' => ViewProjects::class
//    ])->assertCanSeeTableRecords($partners)
//        ->filterTable('neighbourhood', $partner->neighbourhood)
//        ->assertCanSeeTableRecords($partners->where('neighbourhood', $partner->neighbourhood))
//        ->assertCanNotSeeTableRecords($partners->where('neighbourhood', '!==', $partner->neighbourhood));
//});
