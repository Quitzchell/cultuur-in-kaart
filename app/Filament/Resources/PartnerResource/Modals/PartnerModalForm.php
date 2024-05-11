<?php

namespace App\Filament\Resources\PartnerResource\Modals;

use App\Filament\Resources\Interfaces\ModalForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class PartnerModalForm implements ModalForm
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
                ->label('Wijk')
                ->relationship('neighbourhood', 'name')
                ->required()
        ];
    }
}
