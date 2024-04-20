<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewActivities extends ViewRecord
{
    protected static string $resource = ActivityResource::class;

    protected static ?string $title = 'Activiteit bekijken';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
