<?php

namespace App\Filament\Resources\ActivityResource\RelationManagers;

use App\Filament\Resources\PartnerResource;
use Filament\Tables\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnerRelationManager extends RelationManager
{
    protected static string $relationship = 'contactPersonPartner';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Samenwerkingspartner')
                    ->searchable(),
                TextColumn::make('contactPerson.name')
                    ->label('Contactpersoon')
                    ->placeholder('-')
                    ->searchable(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => PartnerResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
