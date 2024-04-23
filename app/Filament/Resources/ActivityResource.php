<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry as InfoTextEntry;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\TextInput as FormTextInput;
use Filament\Forms\Components\Textarea as FormTextArea;
use Filament\Forms\Components\Select as FormSelect;
use Filament\Forms\Components\DatePicker as DatePicker;
use Filament\Infolists\Components\ViewEntry;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                FormSection::make('Algemeen')
                    ->columns()
                    ->schema([
                        FormTextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        FormSelect::make('project_id')
                            ->relationship('project', 'name')
                            ->label('Project')
                            ->required()
                            ->preload()
                            ->searchable(['name'])
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('project_number')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\DatePicker::make('start_date'),
                                Forms\Components\DatePicker::make('end_date'),
                                Forms\Components\TextInput::make('budget_spend')
                                    ->prefix('€')
                                    ->numeric(),
                            ])
                            ->columnSpanFull(),

                        FormSelect::make('task_id')
                            ->relationship('task', 'name')
                            ->label('Taak')
                            ->required(),

                        DatePicker::make('date')
                            ->label('Datum')
                            ->required(),

                        FormSelect::make('neighbourhood_id')
                            ->relationship('neighbourhoods', 'name')
                            ->label('Wijken')
                            ->required()
                            ->multiple()
                            ->preload()
                            ->searchable(['name'])
                            ->columnSpanFull(),
                    ]),

                FormSection::make('Betrokkenen')
                    ->columns()
                    ->schema([
                        FormSelect::make('partners_id')
                            ->relationship('partners', 'name')
                            ->label('Partners')
                            ->required()
                            ->multiple()
                            ->preload()
                            ->searchable(['name'])
                            ->columnSpanFull(),

//                        FormSelect::make('contact_person_id')
//                            ->label('Contact Persoon')
//                            ->relationship('contactPerson', 'name', function (callable $get, Builder $query) {
//                                if ($get('partners_id')) {
//                                    $query->withWhereHas('partners', function ($record) use ($get) {
//                                        $record->where('partner_id', $get('partners_id'));
//                                    })->pluck('name');
//                                }
//                            })
//                            ->required()
//                            ->preload(),
                    ]),

                FormSection::make('Opmerkingen')->schema([
                    FormTextArea::make('comment')
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
                    ->label('Project'),
                TextColumn::make('task.name')
                    ->label('Taak'),
                TextColumn::make('neighbourhoods.name')
                    ->label('Wijken'),
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
            RelationManagers\RelatedActivityRelationManager::class
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns()
            ->schema([
                InfoSection::make('')
                    ->columnSpan(1)
                    ->schema([
                        InfoTextEntry::make('name')
                            ->label('Naam'),
                        InfoTextEntry::make('date')
                            ->label('Datum'),
                        InfoTextEntry::make('project.name')
                            ->label('Project'),
                        InfoTextEntry::make('neighbourhoods.name')
                            ->label('Wijk'),
                        InfoTextEntry::make('task.name')
                            ->label('Taak'),
                    ]),
                InfoSection::make('')
                    ->columnSpan(1)
                    ->schema([
                        InfoTextEntry::make('comment')
                            ->label('Opmerking'),
                        InfoTextEntry::make('partners.name')
                            ->label('Partners'),

                    ]),
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
