<?php

namespace App\Filament\Resources\CoordinatorResource\Pages;

use App\Filament\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewCoordinator extends ViewRecord
{
    protected static string $resource = CoordinatorResource::class;

    public function getTitle(): string|Htmlable
    {
        return __($this->record->name);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Coördinator bewerken')
        ];
    }
}
