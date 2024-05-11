<?php

namespace App\Filament\Resources\PartnerResource\RelationManagers;

use App\Filament\Resources\ProjectResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $title = 'Projecten';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('projects.start_date', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Projectnaam')
                    ->searchable(['projects.name']),
                TextColumn::make('start_date')
                    ->label('Start datum')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('end_date')
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
