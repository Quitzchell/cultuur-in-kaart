<?php

namespace App\Filament\Resources\CoordinatorResource\RelationManagers;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Projecten';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('projects')
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->searchable(),
                TextColumn::make('project.start_date')
                    ->label('Start datum')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('project.end_date')
                    ->label('Eind datum')
                    ->date('d-m-Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => ProjectResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
