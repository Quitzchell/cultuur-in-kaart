<?php

namespace App\Filament\Resources\PartnerResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\Partner;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Activiteiten';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('d-m-Y'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('task.name'),
                Tables\Columns\TextColumn::make('neighbourhoods.name')
                    ->default('-')
                    ->limit(40),
                Tables\Columns\TextColumn::make('partners.name')
                    ->default('-')
                    ->limit(40)
                    ->formatStateUsing(function (string $state, ?Activity $record) {
                        return implode(', ', array_filter(
                            explode(', ', $state),
                            fn($partnerName) => $partnerName !== Partner::find($record->partner_id)->name
                        ));
                    }),
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
                    ->url(fn($record): string => ActivityResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
