<?php

namespace App\Filament\Resources\ContactPersonResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('date')
                    ->label('Datum')
                    ->date('d-m-Y'),
                TextColumn::make('name')
                    ->label('Activiteitnaam'),
                TextColumn::make('task.name')
                    ->label('Taak'),
                TextColumn::make('neighbourhoods.name')
                    ->label('Wijken')
                    ->default('-')
                    ->limit(40),
                TextColumn::make('partners.name')
                    ->label('Samenwerkingspartners')
                    ->default('-')
                    ->limit(40),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => ActivityResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
