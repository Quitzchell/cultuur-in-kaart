<?php

namespace App\Filament\Resources\ContactPersonResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use Filament\Forms\Components\Section;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
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
                TextColumn::make('date')
                    ->label('Datum')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Activiteitnaam')
                    ->searchable(),
                TextColumn::make('task.name')
                    ->label('Taak'),
            ])
            ->filters([
                SelectFilter::make('task')
                    ->relationship('task', 'name')
                    ->columnSpan(1)
                    ->label('Taken')
                    ->preload()
                    ->multiple(),
            ], FiltersLayout::Modal)
            ->filtersFormSchema(fn(array $filters): array => [
                Section::make()
                    ->schema([
                        $filters['task']
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
