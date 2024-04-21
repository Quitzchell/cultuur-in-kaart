<?php

namespace App\Filament\Resources\NeighbourhoodResource\Pages;

use App\Filament\Resources\NeighbourhoodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNeighbourhood extends EditRecord
{
    protected static string $resource = NeighbourhoodResource::class;

    protected static ?string $title = 'Wijk bewerken';

    protected function getHeaderActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Terug'),
            Actions\DeleteAction::make(),
            $this->getSaveFormAction()
                ->label('Opslaan'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
