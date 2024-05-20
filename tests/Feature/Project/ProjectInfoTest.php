<?php

use App\Filament\Resources\ProjectResource;
use App\Models\Project;

/** Render */
it('can render Project info', function () {
    $project = Project::factory()->create();
    $this->get(ProjectResource::getUrl('view', ['record' => $project->getKey()]))->assertSuccessful();
});
