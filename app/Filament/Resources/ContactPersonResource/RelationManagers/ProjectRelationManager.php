<?php

namespace App\Filament\Resources\ContactPersonResource\RelationManagers;

use App\Filament\Resources\ProjectResource;
use Filament\Forms;
use Filament\Forms\Form;
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
            ->recordTitleAttribute('projects')
            ->columns([
                TextColumn::make('project.name'),
                TextColumn::make('project.start_date')
                    ->label('Start datum')
                    ->date('d-m-Y'),
                TextColumn::make('project.end_date')
                    ->label('Eind datum')
                    ->date('d-m-Y'),
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
                    ->url(fn($record): string => ProjectResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
