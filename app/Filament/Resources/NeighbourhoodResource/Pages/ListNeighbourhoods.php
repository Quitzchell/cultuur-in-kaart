<?php

namespace App\Filament\Resources\NeighbourhoodResource\Pages;

use App\Filament\Resources\NeighbourhoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNeighbourhoods extends ListRecords
{
    protected static string $resource = NeighbourhoodResource::class;
    protected static ?string $title = 'Overzicht Wijken';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Wijk toevoegen')
        ];
    }
}
