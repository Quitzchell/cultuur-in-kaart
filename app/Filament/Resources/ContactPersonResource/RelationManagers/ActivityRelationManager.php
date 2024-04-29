<?php

namespace App\Filament\Resources\ContactPersonResource\RelationManagers;

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
                    ->label('Datum')
                    ->date('d-m-Y'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Activiteitnaam'),
                Tables\Columns\TextColumn::make('task.name')
                    ->label('Taak'),
                Tables\Columns\TextColumn::make('neighbourhoods.name')
                    ->label('Wijken')
                    ->default('-')
                    ->limit(40),
                Tables\Columns\TextColumn::make('partners.name')
                    ->label('Samenwerkingspartners')
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
