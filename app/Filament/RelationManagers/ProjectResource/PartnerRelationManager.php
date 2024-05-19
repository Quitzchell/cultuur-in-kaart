<?php

namespace App\Filament\RelationManagers\ProjectResource;

use App\Filament\Resources\PartnerResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnerRelationManager extends RelationManager
{
    protected static string $relationship = 'partners';

    protected static ?string $title = 'Samenwerkingspartners';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('partners.name')
                    ->label('Naam')
                    ->searchable(),
                TextColumn::make('partners.neighbourhood.name')
                    ->label('Wijk')
                    ->placeholder('-'),
                TextColumn::make('partners.primaryContactPerson.name')
                    ->label('Primaire contactpersoon')
                    ->searchable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('neighbourhood')
                    ->relationship('partners.neighbourhood', 'name')
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
