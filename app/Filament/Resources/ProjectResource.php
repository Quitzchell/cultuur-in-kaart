<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\ActivityRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\PartnerRelationManager;
use App\Models\Coordinator;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
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
                Section::make('Algemeen')
                    ->columns()
                    ->schema([
                        TextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('project_number')
                            ->alphaNum()
                            ->label('Projectnummer')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('coordinator_id')
                            ->label('Coördinatoren')
                            ->relationship('coordinator', 'name')
                            ->multiple()
                            ->preload()
                            ->required()
                            ->live(),
                        Select::make('primary_coordinator_id')
                            ->label('Primaire Coördinator')
                            ->relationship('coordinator', 'name', fn(Get $get) => Coordinator::where('id', $get('coordinator_id')))
                            ->disabled(fn(Get $get) => empty($get('coordinator_id'))),
                    ]),

                Group::make()
                    ->columnSpanFull()
                    ->columns()
                    ->schema([
                        Section::make('Data')
                            ->columnSpan(1)
                            ->schema([
                                DatePicker::make('start_date')
                                    ->label('Startdatum')
                                    ->required(),
                                DatePicker::make('end_date')
                                    ->label('Einddatum'),
                            ]),
                        Section::make('Overig')
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('budget_spend')
                                    ->label('Besteed budget')
                                    ->prefix('€')
                                    ->rules('decimal:0,2')
                                    ->numeric()
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Naam')
                    ->searchable(),
                TextColumn::make('neighbourhoods.neighbourhood.name')
                    ->label('Wijken')
                    ->searchable()
                    ->default('-')
                    ->limit(40)
                    ->formatStateUsing(function ($state) {
                        $uniqueNeighbourhoods = array_unique(explode(',', $state));
                        sort($uniqueNeighbourhoods);
                        return implode(', ', $uniqueNeighbourhoods);
                    }),
                TextColumn::make('start_date')
                    ->label('Startdatum')
                    ->date('d-m-Y')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('end_date')
                    ->label('Einddatum')
                    ->date('d-m-Y')
                    ->sortable()
                    ->searchable(),
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
            ActivityRelationManager::class,
            PartnerRelationManager::class
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
                                    ->default('-')
                                    ->inlineLabel(),
                                TextEntry::make('coordinators.coordinator.name')
                                    ->label('Coördinatoren')
                                    ->default('-')
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
