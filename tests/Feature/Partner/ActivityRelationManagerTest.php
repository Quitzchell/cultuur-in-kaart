<?php
//
//use App\Filament\Resources\PartnerResource\Pages\ViewPartner;
//use App\Filament\Resources\PartnerResource\RelationManagers\ActivityRelationManager;
//use App\Models\Activity;
//use App\Models\Partner;
//use App\Models\Task;
//use function Pest\Livewire\livewire;
//
///** Render */
//it('can render related Activities', function () {
//    $partner = Partner::factory()->create();
//    livewire(ActivityRelationManager::class, [
//        'ownerRecord' => $partner,
//        'pageClass' => ViewPartner::class
//    ])->assertSuccessful();
//});
//
//it('can list related Activities', function () {
//    $partner = Partner::factory()->create();
//    $tasks = Task::factory(10)->create();
//    $activities = Activity::factory(10)->create()
//        ->each(function (Activity $activity) use ($tasks, $partner) {
//            $activity->partners()->attach($partner);
//            $activity->task()->associate($tasks->random());
//            $activity->save();
//        });
//
//    livewire(ActivityRelationManager::class, [
//        'ownerRecord' => $partner,
//        'pageClass' => ViewPartner::class
//    ])->assertCanSeeTableRecords($activities)
//        ->assertCountTableRecords(10)
//        ->assertCanRenderTableColumn('date')
//        ->assertTableColumnExists('date')
//        ->assertCanRenderTableColumn('name')
//        ->assertTableColumnExists('name')
//        ->assertCanRenderTableColumn('task.name')
//        ->assertTableColumnExists('task.name');
//});
//
///** Sort */
//it('can sort related Activities by Date', function () {
//    $partner = Partner::factory()->create();
//    $activities = Activity::factory(10)->create()
//        ->each(function (Activity $activity) use ($partner) {
//            $activity->partners()->attach($partner);
//        });
//
//    livewire(ActivityRelationManager::class, [
//        'ownerRecord' => $partner,
//        'pageClass' => ViewPartner::class
//    ])->sortTable('date')
//        ->assertCanSeeTableRecords($activities->sortBy('date'), inOrder: true)
//        ->sortTable('date', 'desc')
//        ->assertCanSeeTableRecords($activities->sortByDesc('date'), inOrder: true);
//});
//
///** Search */
//it('can search related Activities by name', function () {
//    $partner = partner::factory()->create();
//    $activities = Activity::factory(10)->create()
//        ->each(function (Activity $activity) use ($partner) {
//            $activity->partners()->attach($partner);
//        });
//
//    $name = $activities->first()->name;
//    livewire(ActivityRelationManager::class, [
//        'ownerRecord' => $partner,
//        'pageClass' => ViewPartner::class
//    ])->searchTable($name)
//        ->assertCanSeeTableRecords($activities->where('name', $name))
//        ->assertCanNotSeeTableRecords($activities->where('name', '!==', $name));
//});
//
///** Filter */
//it('can filter related Activities by tasks', function () {
//    $partner = Partner::factory()->create();
//    $tasks = Task::factory(10)->create();
//    $activities = collect();
//
//    foreach ($tasks as $task) {
//        $activity = Activity::factory()->create();
//        $activity->partners()->attach($partner);
//        $activity->task()->associate($task);
//        $activity->save();
//        $activities->push($activity);
//    }
//
//    $task = $tasks->first();
//    $filteredActivities = $activities->filter(function ($activity) use ($task) {
//        return $activity->task_id === $task->id;
//    });
//    livewire(ActivityRelationManager::class, [
//        'ownerRecord' => $partner,
//        'pageClass' => ViewPartner::class
//    ])->assertCanSeeTableRecords($activities)
//        ->filterTable('task', $task->getKey())
//        ->assertCanSeeTableRecords($filteredActivities)
//        ->assertCanNotSeeTableRecords($activities->diff($filteredActivities));
//});
