<?php

namespace App\Filament\Resources\ContactPersonResource\Modals;

use App\Filament\Resources\Interfaces\ModalForm;
use Filament\Forms;

class ContactPersonModalForm implements ModalForm
{
    public static function getForm(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->required()
                ->email()
                ->maxLength(255),
            Forms\Components\TextInput::make('phone')
                ->required()
                ->tel()
                ->maxLength(255),
            Forms\Components\Textarea::make('comment')
                ->columnSpanFull()
        ];
    }
}
