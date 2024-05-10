<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Activiteiten';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('date')
                    ->label('Datum')
                    ->sortable()
                    ->date('d-m-Y'),
                TextColumn::make('name')
                    ->label('Activiteit'),
                TextColumn::make('task.name')
                    ->label('Taak'),
                TextColumn::make('partners.name')
                    ->label('Partner(s)')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('task')
                    ->relationship('task', 'name')
                    ->label('Taak')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('partner')
                    ->relationship('partners', 'name',
                        fn($query) => $query->join('activities', 'activities.id', 'activity_partner.activity_id')
                            ->where('activities.project_id', $this->ownerRecord->getKey()))
                    ->label('Samenwerkingspartners')
                    ->preload()
                    ->multiple(),
            ])
            ->headerActions([])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => ActivityResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
