<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Resources\PartnerResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerRelationManager extends RelationManager
{
    protected static string $relationship = 'partners';

    protected static ?string $title = 'Samenwerkingspartners';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Naam'),
                Tables\Columns\TextColumn::make('partner.neighbourhood.name')
                    ->label('Wijk'),
                Tables\Columns\TextColumn::make('partner.primaryContactPerson.name')
                    ->label('Primair contactpersoon'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => PartnerResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
