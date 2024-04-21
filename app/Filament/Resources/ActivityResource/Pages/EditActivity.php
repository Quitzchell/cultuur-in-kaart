<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

    protected static ?string $title = 'Activiteit bewerken';

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
