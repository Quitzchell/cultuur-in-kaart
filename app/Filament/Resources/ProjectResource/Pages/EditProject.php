<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static ?string $title = 'Project bewerken';

    protected function getHeaderActions(): array
    {
        return [
            $this->getCancelFormAction()->label('Terug'),
            Actions\DeleteAction::make(),
            $this->getSaveFormAction()->label('Opslaan'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
