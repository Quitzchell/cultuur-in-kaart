<?php

namespace App\Filament\Resources\ActivityResource\RelationManagers;

use App\Filament\Resources\ActivityResource;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RelatedActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'relatedActivities';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
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
