<?php

namespace App\Filament\Resources\NeighbourhoodResource\Pages;

use App\Enums\Coordinator\Role;
use App\Filament\Resources\NeighbourhoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListNeighbourhoods extends ListRecords
{
    protected static string $resource = NeighbourhoodResource::class;

    protected static ?string $title = 'Wijkenoverzicht';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Wijk toevoegen')
        ];
    }
}
