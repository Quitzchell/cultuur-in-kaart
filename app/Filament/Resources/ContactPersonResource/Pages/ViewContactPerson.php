<?php

namespace App\Filament\Resources\ContactPersonResource\Pages;

use App\Filament\Resources\ContactPersonResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewContactPerson extends ViewRecord
{
    protected static string $resource = ContactPersonResource::class;
    public function getTitle(): string|Htmlable
    {
        return __($this->record->name);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Contactpersoon bewerken'),
        ];
    }
}
