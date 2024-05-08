<?php

namespace App\Filament\Resources\ActivityResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use App\Models\Activity;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RelatedActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'relatedActivities';

    protected static ?string $title = 'Gerelateerde activiteiten';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Activity::query()
                    ->where('project_id', $this->ownerRecord->project_id)
                    ->WhereNot('id', $this->ownerRecord->getKey());
            })
            ->recordTitleAttribute('activities')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('d-m-Y'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Activiteit'),
                Tables\Columns\TextColumn::make('task.name')
                    ->label('Taak'),
                Tables\Columns\TextColumn::make('neighbourhoods.name')
                    ->label('Wijken')
                    ->default('-')
                    ->words(4),
                Tables\Columns\TextColumn::make('partners.name')
                    ->label('Partners')
                    ->default('-'),

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
