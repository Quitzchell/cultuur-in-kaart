<?php

namespace App\Filament\Resources;

use App\Filament\Modals\ContactPersonModal;
use App\Filament\Modals\PartnerModal;
use App\Filament\Modals\ProjectModal;
use App\Filament\RelationManagers\ActivityResource\ActivityRelationManager;
use App\Filament\RelationManagers\ActivityResource\PartnerContactPersonRelationManager;
use App\Filament\Resources\ActivityResource\Pages;
use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section as infoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Activiteiten';

    protected static ?string $modelLabel = 'Activiteit';

    protected static ?string $pluralModelLabel = 'Activiteiten';

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
                            ->maxLength(120)
                            ->columnSpanFull(),
                        Select::make('project_id')
                            ->createOptionForm(ProjectModal::getForm())
                            ->editOptionForm(ProjectModal::getForm())
                            ->relationship('project', 'name')
                            ->label('Project')
                            ->live()
                            ->required()
                            ->preload()
                            ->searchable(['name'])
                            ->columnSpanFull(),
                        Select::make('neighbourhood_id')
                            ->relationship('neighbourhood', 'name')
                            ->label('Wijk')
                            ->required()
                            ->preload(),
                        Select::make('task_id')
                            ->relationship('task', 'name')
                            ->label('Taak')
                            ->required()
                            ->preload(),
                        Select::make('discipline_id')
                            ->relationship('discipline', 'name')
                            ->label('Discipline'),
                        DatePicker::make('date')
                            ->label('Datum')
                            ->required(),
                    ]),

                Section::make('Betrokkenen')
                    ->columns()
                    ->schema([
                        Select::make('coordinators_id')
                            ->relationship('coordinators', 'name',
                                function (Builder $query, Get $get) {
                                    $project = Project::find($get('project_id'));
                                    $coordinators = $project?->coordinators()->pluck('coordinators.id');

                                    return $query->whereKey($coordinators?->unique());
                                })
                            ->label('Coördinatoren')
                            ->required()
                            ->multiple()
                            ->preload()
                            ->columnSpanFull(),

                        Repeater::make('activityPartnerContactPerson')
                            ->relationship()
                            ->label('Contactpersonen')
                            ->addActionLabel('Contactpersoon toevoegen')
                            ->schema([
                                Select::make('partner_id')
                                    ->relationship('partner', 'name')
                                    ->label('Partner')
                                    ->createOptionForm(PartnerModal::getForm())
                                    ->options(Partner::pluck('name', 'id'))
                                    ->live()
                                    ->required()
                                    ->preload()
                                    ->searchable(['name']),
                                Select::make('contact_person_id')
                                    ->relationship('contactPerson', 'name')
                                    ->label('Contactpersoon')
                                    ->createOptionForm(ContactPersonModal::getForm())
                                    ->createOptionUsing(function (array $data, $get): int {
                                        $partner = Partner::find($get('partner_id'));
                                        $contactPerson = $partner->contactPeople()->create($data)->getKey();
                                        $partner->contactPeople()->sync([$contactPerson]);

                                        return $contactPerson->getKey();
                                    })
                                    ->options(fn ($get) => ContactPerson::query()
                                        ->join('contact_person_partner', 'contact_person_partner.contact_person_id', 'contact_people.id')
                                        ->where('contact_person_partner.partner_id', $get('partner_id'))
                                        ->pluck('contact_people.name', 'contact_people.id'))
                                    ->required()
                                    ->preload()
                                    ->disabled(fn (Get $get) => $get('partner_id') === null),
                            ])
                            ->mutateRelationshipDataBeforeCreateUsing(function (?Activity $activity, array $data): array {
                                $activity?->partners()->sync($data['partner_id']);

                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function (?Activity $activity, array $data): array {
                                $activity->partners()->sync($data['partner_id']);

                                return $data;
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Opmerkingen')
                    ->schema([
                        Textarea::make('comment')
                            ->label(''),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->label('Datum')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Activiteitnaam')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label('Projectnaam')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('task.name')
                    ->label('Taak')
                    ->placeholder('-'),
                TextColumn::make('neighbourhood.name')
                    ->label('Wijk')
                    ->placeholder('-')
                    ->searchable()
                    ->limit(40),
            ])
            ->filters([
                SelectFilter::make('project')
                    ->relationship('project', 'name')
                    ->label('Projectnaam')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('neighbourhood')
                    ->relationship('neighbourhood', 'name')
                    ->label('Wijk')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('task')
                    ->relationship('task', 'name')
                    ->label('Taak'),
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
                                fn (Builder $query, $date) => $query->whereDate('date', '>=', $date)
                            )
                            ->when(
                                $data['date_until'],
                                fn (Builder $query, $date) => $query->whereDate('date', '<=', $date)
                            );
                    }),
            ], FiltersLayout::Modal)
            ->actions([
                ViewAction::make()
                    ->label(''),
                EditAction::make()
                    ->label(''),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(['default' => 1, 'lg' => 2])
            ->schema([
                infoSection::make('')
                    ->schema([
                        TextEntry::make('date')
                            ->label('Datum')
                            ->date('l j F Y', 'Europe/Amsterdam')
                            ->inlineLabel(),
                        TextEntry::make('project.name')
                            ->label('Projectnaam')
                            ->inlineLabel(),

                        TextEntry::make('neighbourhood.name')
                            ->label('Wijk')
                            ->formatStateUsing(function ($state) {
                                $neighbourhoods = explode(', ', $state);
                                sort($neighbourhoods);

                                return implode(', ', $neighbourhoods);
                            })
                            ->inlineLabel(),
                        TextEntry::make('task.name')
                            ->label('Taak')
                            ->inlineLabel(),
                        TextEntry::make('discipline.name')
                            ->label('Discipline')
                            ->inlineLabel(),
                        TextEntry::make('Coordinators.name')
                            ->label('Coördinatoren')
                            ->placeholder('-')
                            ->inlineLabel(),
                    ])->columnSpan(1),

                Group::make()
                    ->schema([
                        InfoSection::make('')
                            ->schema([
                                TextEntry::make('comment')
                                    ->label('Opmerking')
                                    ->inlineLabel()
                                    ->placeholder('-'),
                            ]),
                    ])->columnSpan(1),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivityRelationManager::class,
            PartnerContactPersonRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'view' => Pages\ViewActivity::route('/{record}'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
