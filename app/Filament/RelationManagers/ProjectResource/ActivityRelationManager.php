<?php

namespace App\Filament\RelationManagers\ProjectResource;

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
                    ->label('Samenwerkingspartners')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('task')
                    ->relationship('task', 'name')
                    ->label('Taak')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('partner')
                    ->relationship('partners', 'name', fn ($query) => $query
                        ->whereIn('activity_partner.activity_id', $this->ownerRecord->activities()->pluck('id')))
                    ->label('Samenwerkingspartners')
                    ->preload()
                    ->multiple(),
            ])
            ->headerActions([])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn ($record): string => ActivityResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
