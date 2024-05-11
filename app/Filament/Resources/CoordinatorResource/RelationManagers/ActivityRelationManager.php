<?php

namespace App\Filament\Resources\CoordinatorResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

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
                    ->label('Wijken')
                    ->formatStateUsing(function ($state) {
                        $neighbourhoods = explode(', ', $state);
                        sort($neighbourhoods);
                        return implode(', ', $neighbourhoods);
                    })
                    ->placeholder('-')
                    ->limit(40),
                Tables\Columns\TextColumn::make('partners.name')
                    ->default('-')
                    ->limit(40),
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
