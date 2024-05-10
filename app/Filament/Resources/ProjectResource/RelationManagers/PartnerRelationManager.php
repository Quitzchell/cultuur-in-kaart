<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Resources\PartnerResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

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
                Tables\Filters\SelectFilter::make('neighbourhood')
                    ->relationship('partner.neighbourhood', 'name'),
                Tables\Filters\SelectFilter::make('primaryContactPerson')
                    ->relationship('partner.primaryContactPerson', 'name',
                        function ($query) {
                            return $query->whereIn('id', $this->ownerRecord->partners->map(function ($pivotContactPersonPartner) {
                                return $pivotContactPersonPartner->partner->primaryContactPerson->getKey();
                            }));
                        })
                    ->multiple()
                    ->preload()
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => PartnerResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
