<?php

namespace App\Filament\Resources\CoordinatorResource\Pages;

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
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->hidden(Auth::user()->getKey() === $this->getRecord()->getKey()),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
