<?php

namespace App\Filament\RelationManagers\ContactPersonResource;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $title = 'Projecten';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('project.start_date', 'desc')
            ->columns([
                TextColumn::make('project.name')
                    ->label('Projectnaam')
                    ->searchable(['project.name']),
                TextColumn::make('project.start_date')
                    ->label('Startdatum')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('project.end_date')
                    ->label('Einddatum')
                    ->date('d-m-Y')
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->url(fn($record): string => ProjectResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
