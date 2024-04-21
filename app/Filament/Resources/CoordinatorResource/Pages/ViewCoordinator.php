<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCoordinator extends ViewRecord
{
    protected static string $resource = CoordinatorResource::class;

    protected static ?string $title = 'Coördinator bekijken';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Coördinator bewerken')
        ];
    }
}
