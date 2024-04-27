<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\PartnersRelationManager;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                TextInput::make('name')
                    ->label('Naam')
                    ->required()
                    ->maxLength(255),
                TextInput::make('project_number')
                    ->label('Projectnummer')
                    ->required()
                    ->numeric(),
                DatePicker::make('start_date')
                    ->label('Startdatum')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Einddatum'),
                Select::make('coordinator_id')
                    ->relationship('coordinators', 'name')
                    ->label('Coördinatoren')
                    ->required()
                    ->multiple()
                    ->preload()
                    ->live(),
                Select::make('primary_coordinator_id')
                    ->relationship(
                        'primaryCoordinator',
                        'name',
                        function (Builder $query, Get $get) {
                            return $query->whereIn('id', $get('coordinator_id'));
                        }
                    )
                    ->disabled(fn(Get $get) => empty($get('coordinator_id')))
                    ->label('Primaire Coördinator')
                    ->required(),
                TextInput::make('budget_spend')
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
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('neighbourhoods.neighbourhood.name')
                    ->default('-')
                    ->limit(40),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label(''),
                EditAction::make()
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
            'view' => Pages\ListProjects::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
