<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewPartner extends ViewRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string|Htmlable
    {
        return __($this->record->name);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Samenwerkingspartner bewerken'),
        ];
    }
}
