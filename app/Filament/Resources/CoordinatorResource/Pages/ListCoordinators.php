<?php

namespace App\Filament\Resources\CoordinatorResource\Pages;

use App\Enums\Coordinator\Role;
use App\Filament\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListCoordinators extends ListRecords
{
    protected static string $resource = CoordinatorResource::class;

    protected static ?string $title = 'Overzicht coördinatoren';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Coördinator toevoegen')
                ->hidden(Auth::user()->role !== Role::Administrator->name),
        ];
    }
}
