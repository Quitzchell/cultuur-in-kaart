<?php

namespace App\Filament\Resources\ContactPersonResource\Pages;

use App\Filament\Resources\ContactPersonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactPerson extends EditRecord
{
    protected static string $resource = ContactPersonResource::class;

    protected static ?string $title = 'Contactpersoon bewerken';

    protected function getHeaderActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Terug'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
        ];
    }
}
