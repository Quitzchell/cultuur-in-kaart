<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCoordinator extends EditRecord
{
    protected static string $resource = CoordinatorResource::class;

    protected static ?string $title = 'Coördinator bewerken';

    protected function getHeaderActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Terug'),
            Actions\DeleteAction::make()
                ->hidden(Auth::user()->getKey() === $this->getRecord()->getKey()),
            $this->getSaveFormAction()
                ->label('Opslaan'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
