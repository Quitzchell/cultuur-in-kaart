<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\PartnersRelationManager;
use App\Models\Activity;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
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
                    ->label('Primaire Coördinator')
                    ->relationship(
                        'coordinator',
                        'name',
                        function (Builder $query, ?Project $record) {
                            $coordinatorsIds = $record?->activities()->with('coordinators')->get()
                                ->flatMap(fn(Activity $activity) => $activity->coordinators->pluck('id'));
                            return $query->whereKey($coordinatorsIds?->unique());
                        }
                    )->disabled(function (?Project $record) {
                        return empty($record?->activities()->with('coordinators')->get()
                            ->flatMap(fn(Activity $activity) => $activity->coordinators));
                    }),
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
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date('d-m-Y')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('end_date')
                    ->date('d-m-Y')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('neighbourhoods.neighbourhood.name')
                    ->searchable()
                    ->default('-')
                    ->formatStateUsing(function ($state) {
                        $uniqueNeighbourhoods = array_unique(explode(',', $state));
                        sort($uniqueNeighbourhoods);
                        return implode(', ', $uniqueNeighbourhoods);
                    })
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivitiesRelationManager::class,
            PartnersRelationManager::class
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(['default' => 1, 'lg' => 2])
            ->schema([
                Grid::make()
                    ->columnSpan(1)
                    ->schema([
                        InfoSection::make('')
                            ->columns()
                            ->schema([
                                TextEntry::make('start_date')
                                    ->label('Startdatum')
                                    ->date('d-m-Y'),
                                TextEntry::make('end_date')
                                    ->label('Einddatum')
                                    ->date('d-m-Y'),
                            ]),

                        InfoSection::make('')
                            ->schema([
                                TextEntry::make('coordinator.name')
                                    ->label('Primaire Coördinator')
                                    ->inlineLabel(),
                                TextEntry::make('coordinators.coordinator.name')
                                    ->label('Coördinatoren')
                                    ->inlineLabel(),
                            ]),
                    ]),
                Grid::make()
                    ->columnSpan(1)
                    ->schema([
                        InfoSection::make('')
                            ->schema([
                                TextEntry::make('activities.partners.name')
                                    ->label('Partners'),
                                TextEntry::make('neighbourhoods.neighbourhood.name')
                                    ->label('Wijken')
                                    ->distinctList()
                            ])
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProjects::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
