<?php

namespace App\Filament\Modals;

use App\Filament\Modals\Interfaces\ModalForm;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ContactPersonModal implements ModalForm
{
    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->required()
                ->email()
                ->maxLength(255),
            TextInput::make('phone')
                ->required()
                ->tel()
                ->maxLength(255),
            Textarea::make('comment')
                ->columnSpanFull(),
        ];
    }
}
