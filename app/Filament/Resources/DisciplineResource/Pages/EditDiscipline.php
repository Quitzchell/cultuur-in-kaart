<?php

namespace App\Filament\Resources\DisciplineResource\Pages;

use App\Filament\Resources\DisciplineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscipline extends EditRecord
{
    protected static string $resource = DisciplineResource::class;

    protected static ?string $title = 'Discipline bewerken';

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

    public function getRelationManagers(): array
    {
        return [];
    }
}
