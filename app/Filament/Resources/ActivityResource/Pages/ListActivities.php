<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected static ?string $title = 'Activiteitenoverzicht';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Activiteit toevoegen'),
        ];
    }
}
