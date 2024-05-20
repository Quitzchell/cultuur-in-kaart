<?php

namespace App\Filament\RelationManagers\ActivityResource;

use App\Filament\Resources\ContactPersonResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartnerContactPersonRelationManager extends RelationManager
{
    protected static string $relationship = 'activityPartnerContactPerson';

    protected static ?string $title = 'Contactpersonen';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contactPerson.name')
                    ->label('Contactpersoon')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('contactPerson.phone')
                    ->label('Telefoonnummer')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('partner.name')
                    ->label('Samenwerkingspartner')
                    ->searchable(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn ($record): string => ContactPersonResource::getUrl('view', ['record' => $record->contactPerson])),
            ]);
    }
}
