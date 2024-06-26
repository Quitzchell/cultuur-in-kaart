<?php

namespace App\Filament\RelationManagers\PartnerResource;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Projecten';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => Project::whereIn('id', $query->pluck('project_id')))
            ->defaultSort('start_date', 'desc')
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
                ViewAction::make()
                    ->label('')
                    ->url(fn ($record): string => ProjectResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
