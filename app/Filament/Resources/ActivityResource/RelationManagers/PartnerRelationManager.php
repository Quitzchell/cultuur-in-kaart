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
    protected static string $relationship = 'partners';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->searchable(),
                TextColumn::make('neighbourhood.name')
                    ->label('Wijk')
                    ->placeholder('-'),
                TextColumn::make('primaryContactPerson.name')
                    ->label('Primaire contactpersoon')
                    ->searchable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('neighbourhood')
                    ->relationship('neighbourhood', 'name')
                    ->label('Wijken')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => PartnerResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
