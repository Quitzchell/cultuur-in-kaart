<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use Carbon\Carbon;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Activiteiten';

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
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->label('Project')
                            ->required()
                            ->preload()
                            ->searchable(['name'])
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('project_number')
                                    ->required()
                                    ->numeric(),
                                DatePicker::make('start_date'),
                                DatePicker::make('end_date'),
                                TextInput::make('budget_spend')
                                    ->prefix('€')
                                    ->numeric(),
                            ])
                            ->columnSpanFull(),

                        Select::make('task_id')
                            ->relationship('task', 'name')
                            ->label('Taak')
                            ->required(),

                        DatePicker::make('date')
                            ->label('Datum')
                            ->required(),

                        CheckboxList::make('neighbourhood_id')
                            ->bulkToggleable()
                            ->relationship('neighbourhoods', 'name')
                            ->label('Wijken')
                            ->required()
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Betrokkenen')
                    ->columns()
                    ->schema([
                        Select::make('partners_id')
                            ->relationship('partners', 'name')
                            ->label('Partners')
                            ->live()
                            ->required()
                            ->multiple()
                            ->preload()
                            ->searchable(['name'])
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('zip')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('street')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('house_number')
                                    ->required()
                                    ->numeric()
                                    ->maxLength(10),
                                TextInput::make('house_number_addition')
                                    ->maxLength(10),
                                Select::make('contact_person_id')
                                    ->label('Contactpersoon')
                                    ->relationship('contactPerson', 'name')
                                    ->preload()
                            ]),
                        Select::make('contact_person_id')
                            ->label('Contactpersoon')
                            ->relationship(
                                'contactPerson',
                                'name',
                                function (Builder $query, Get $get) {
                                    return $query->whereIn('id', $get('partners_id'));
                                })
                            ->disabled(fn(Get $get) => empty($get('partners_id')))

                    ]),

                Section::make('Opmerkingen')->schema([
                    Textarea::make('comment')
                        ->label('')
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Datum')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Activiteit')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable(),
                TextColumn::make('task.name')
                    ->label('Taak'),
                TextColumn::make('neighbourhoods.name')
                    ->label('Wijken')
                    ->searchable()
                    ->default('-')
                    ->limit(40),
            ])
            ->filters([
                SelectFilter::make('project')
                    ->relationship('project', 'name')
                    ->label('Projecten')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('neighbourhoods')
                    ->relationship('neighbourhoods', 'name')
                    ->label('Wijken')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('task')
                    ->relationship('task', 'name'),
                Tables\Filters\Filter::make('date')->form([
                    DatePicker::make('date_from')
                        ->label('Datum vanaf')
                        ->columnSpanFull(),
                    DatePicker::make('date_until')
                        ->label('Datum tot')
                        ->columnSpanFull(),
                ])->columns()
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn(Builder $query, $date) => $query->whereDate('date', '>=', $date)
                            )
                            ->when(
                                $data['date_until'],
                                fn(Builder $query, $date) => $query->whereDate('date', '<=', $date)
                            );
                    })
            ], FiltersLayout::Modal)
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
            RelationManagers\RelatedActivityRelationManager::class
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    InfoSection::make('')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Naam'),
                            TextEntry::make('date')
                                ->label('Datum')
                                ->formatStateUsing(function ($state, $component) {
                                    return ucfirst(Carbon::parse($state)
                                        ->setTimezone($component->getTimezone())
                                        ->translatedFormat('l j F Y'));
                                }),
                            TextEntry::make('project.name')
                                ->label('Project'),
                            TextEntry::make('neighbourhoods.name')
                                ->label('Wijk'),
                            TextEntry::make('task.name')
                                ->label('Taak'),
                        ]),

                    Grid::make(1)
                        ->schema([
                            InfoSection::make('')
                                ->schema([
                                    Group::make()->columns()->schema([
                                        TextEntry::make('partners.name')
                                            ->label('Partners'),
                                        TextEntry::make('contactPerson.name')
                                            ->label('Contactpersoon'),
                                    ]),
                                ]),
                            InfoSection::make('')
                                ->schema([
                                    TextEntry::make('comment')
                                        ->label('Opmerking'),
                                ]),
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'view' => Pages\ViewActivities::route('/{record}'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
