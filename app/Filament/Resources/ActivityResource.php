<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Infolists\Components\RelationshipTable;
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
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('Algemeen')->schema([
                    FormTextInput::make('name')
                        ->label('Naam')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    DatePicker::make('date')
                        ->label('Datum')
                        ->required(),
                    FormSelect::make('task_id')
                        ->label('Taak')
                        ->relationship('task', 'name')
                        ->required(),
                ])->columns(),

                FormSection::make('Project Information')->schema([
                    FormSelect::make('project_id')
                        ->label('Project')
                        ->relationship('project', 'name')
                        ->preload()
                        ->searchable(['name']),
                    FormSelect::make('partners_id')
                        ->label('Partner')
                        ->relationship('partners', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(['name']),
                    FormSelect::make('contact_person_id')
                        ->label('Contact Persoon')
                        ->relationship('contactPerson', 'name')
                        ->preload()
                        ->searchable(['name']),

                    FormSelect::make('neighbourhood_id')
                        ->label('Wijk')
                        ->relationship('neighbourhood', 'name')
                        ->preload()
                        ->searchable(['name']),
                ])->columns(),

                FormSection::make('Opmerkingen')->schema([
                    FormTextArea::make('comment')
                        ->label('')
                        ->columnSpanFull()
                ])
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
                TextColumn::make('neighbourhood.name')
                    ->label('Wijk'),
            ])
            ->filters([
                SelectFilter::make('project.name')
                    ->relationship('project', 'name')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('neighbourhood.name')
                    ->label('Wijk')
                    ->relationship('neighbourhood', 'name')
                    ->multiple()
                    ->preload()
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                ViewAction::make(),
                EditAction::make(),
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
            RelationManagers\ActivityRelationManager::class
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
                        InfoTextEntry::make('neighbourhood.name')
                            ->label('Wijk'),
                        InfoTextEntry::make('task.name')
                            ->label('Taak'),
                    ]),
                InfoSection::make('')
                    ->columnSpan(1)
                    ->schema([
                        InfoTextEntry::make('comment')
                            ->label('Opmerking')
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
