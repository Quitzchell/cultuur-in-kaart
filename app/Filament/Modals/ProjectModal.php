<?php

namespace App\Filament\Modals;

use App\Filament\Modals\Interfaces\ModalForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ProjectModal implements ModalForm
{

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Naam')
                ->required()
                ->maxLength(255),
            TextInput::make('project_number')
                ->label('Projectnummer')
                ->alphaNum()
                ->required(),
            DatePicker::make('start_date')
                ->label('Startdatum')
                ->required(),
            DatePicker::make('end_date')
                ->label('Einddatum'),
            Select::make('coordinator_id')
                ->relationship('coordinators', 'name')
                ->multiple()
                ->preload()
                ->required()
                ->label('Coördinator')
        ];
    }
}
