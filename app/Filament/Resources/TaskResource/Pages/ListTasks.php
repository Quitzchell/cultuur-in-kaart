<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected static ?string $title = 'Overzicht taken';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Taak toevoegen'),
        ];
    }
}
