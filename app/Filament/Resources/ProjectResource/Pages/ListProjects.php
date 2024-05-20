<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected static ?string $title = 'Overzicht projecten';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Project toevoegen'),
        ];
    }
}
