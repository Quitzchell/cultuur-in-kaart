<?php

namespace App\Filament\Resources\PartnerResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Activiteiten';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Activiteitnaam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('task.name')
                    ->label('Taak'),
                Tables\Columns\TextColumn::make('neighbourhoods.name')
                    ->label('Wijken')
                    ->formatStateUsing(function ($state) {
                        $neighbourhoods = explode(', ', $state);
                        sort($neighbourhoods);
                        return implode(', ', $neighbourhoods);
                    })
                    ->placeholder('-')
                    ->limit(40),
                Tables\Columns\TextColumn::make('partners.name')
                    ->label('Andere samenwerkingspartners')
                    ->limit(40)
                    ->formatStateUsing(function (string $state, ?Activity $record) {
                        $filteredArray = array_filter(explode(', ', $state), function ($partnerName) use ($record) {
                            return $partnerName !== Partner::find($record->partner_id)->name;
                        });
                        return !empty($filteredArray) ? implode(', ', $filteredArray) : '-';
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
