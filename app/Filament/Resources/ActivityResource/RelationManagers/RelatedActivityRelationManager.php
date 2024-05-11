<?php

namespace App\Filament\Resources\ActivityResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use App\Models\Activity;
use Filament\Forms\Components\Section;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
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
                    ->whereNot('id', $this->ownerRecord->getKey());
            })
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->label('Datum')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Activiteit'),
                TextColumn::make('task.name')
                    ->label('Taak'),
                TextColumn::make('neighbourhoods.name')
                    ->label('Wijken')
                    ->formatStateUsing(function ($state) {
                        $neighbourhoods = explode(', ', $state);
                        sort($neighbourhoods);
                        return implode(', ', $neighbourhoods);
                    })
                    ->default('-')
                    ->words(4),
            ])
            ->filters([
                SelectFilter::make('task')
                    ->relationship('task', 'name')
                    ->columnSpan(1)
                    ->label('')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('neighbourhood')
                    ->columnSpan(1)
                    ->relationship('neighbourhoods', 'name')
                    ->label('')
                    ->preload()
                    ->multiple(),
            ], FiltersLayout::Modal)
            ->filtersFormSchema(fn(array $filters): array => [
                Section::make('Taken')
                    ->schema([
                        $filters['task']
                    ]),
                Section::make('Wijken')
                    ->schema([
                        $filters['neighbourhood']
                    ]),
            ])
            ->filtersFormWidth(MaxWidth::ExtraLarge)
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => ActivityResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
