<?php

namespace App\Filament\Modals;

use App\Filament\Modals\Interfaces\ModalForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class PartnerModal implements ModalForm
{

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Naam')
                ->required()
                ->maxLength(255),
            TextInput::make('street')
                ->label('Straat')
                ->required()
                ->maxLength(255),
            TextInput::make('house_number')
                ->label('Huisnummer')
                ->required()
                ->numeric()
                ->maxLength(10),
            TextInput::make('house_number_addition')
                ->label('Huisnummertoevoeging')
                ->maxLength(10)
                ->live(true)
                ->afterStateUpdated(function (TextInput $component, ?string $state) {
                    isset($state) && $component->state(strtoupper($state));
                }),
            TextInput::make('zip')
                ->label('Postcode')
                ->required()
                ->maxLength(255),
            TextInput::make('city')
                ->label('Stad')
                ->required()
                ->maxLength(255),
            Select::make('neighbourhood_id')
                ->relationship('neighbourhood', 'name')
                ->label('Wijk')
                ->required()
        ];
    }
}
