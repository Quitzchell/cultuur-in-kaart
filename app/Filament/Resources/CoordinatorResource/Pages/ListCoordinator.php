<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\Coordinator\Role;
use App\Filament\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListCoordinator extends ListRecords
{
    protected static string $resource = CoordinatorResource::class;

    protected static ?string $title = 'Coördinatoroverzicht';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->hidden(Auth::user()->role !== Role::Administrator->name),
        ];
    }
}
