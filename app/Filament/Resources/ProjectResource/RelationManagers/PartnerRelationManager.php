<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

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
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('partner.name')
                    ->label('Naam'),
                TextColumn::make('partner.neighbourhood.name')
                    ->label('Wijk')
                    ->placeholder('-'),
                TextColumn::make('partner.primaryContactPerson.name')
                    ->label('Primair contactpersoon')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('neighbourhood')
                    ->relationship('partner.neighbourhood', 'name')
                    ->label('Wijken')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('primaryContactPerson')
                    ->relationship('partner.primaryContactPerson', 'name',
                        function ($query) {
                            return $query->whereIn('id', $this->ownerRecord->partners->map(function ($pivotContactPersonPartner) {
                                return $pivotContactPersonPartner->partner->primaryContactPerson->getKey();
                            }));
                        })
                    ->label('Contactpersonen')
                    ->multiple()
                    ->preload()
                    ->searchable()
            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => PartnerResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
