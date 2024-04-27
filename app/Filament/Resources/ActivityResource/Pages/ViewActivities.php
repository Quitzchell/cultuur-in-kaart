<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewActivities extends ViewRecord
{
    protected static string $resource = ActivityResource::class;


    public function getTitle(): string|Htmlable
    {
        return __($this->record->name);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Activiteit bewerken'),
        ];
    }
}
