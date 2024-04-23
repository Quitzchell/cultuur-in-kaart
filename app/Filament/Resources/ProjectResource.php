<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\PartnersRelationManager;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Projecten';

    protected static ?string $navigationGroup = 'Projecten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Naam')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('project_number')
                    ->label('Projectnummer')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Startdatum')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Einddatum'),
                Forms\Components\Select::make('coordinator_id')
                    ->relationship('coordinators', 'name')
                    ->label('Coördinatoren')
                    ->required()
                    ->multiple()
                    ->preload(),
                // todo: make selectable related on selected coordinators
                Forms\Components\Select::make('primary_coordinator_id')
                    ->relationship('primaryCoordinator', 'name')
                    ->label('Primaire Coördinator')
                    ->required()
                    ->preload()
                    ->searchable(['name']),
                Forms\Components\TextInput::make('budget_spend')
                    ->label('Besteed budget')
                    ->prefix('€')
                    ->rules('decimal:0,2')
                    ->numeric()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->with(['neighbourhoods.neighbourhood']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('neighbourhoods.neighbourhood.name')
                    ->default('-')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivitiesRelationManager::class,
            PartnersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
