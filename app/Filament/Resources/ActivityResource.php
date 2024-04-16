<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Algemeen')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Naam')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\DatePicker::make('date')
                        ->label('Datum')
                        ->required(),
                    Forms\Components\Select::make('task_id')
                        ->label('Taak')
                        ->relationship('task', 'name')
                        ->required(),
                ])->columns(),

                Forms\Components\Section::make('Project Information')->schema([
                    Forms\Components\Select::make('project_id')
                        ->label('Project')
                        ->relationship('project', 'name')
                        ->preload()
                        ->searchable(['name']),
                    Forms\Components\Select::make('partners_id')
                        ->label('Partner')
                        ->relationship('partners', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(['name']),
                    Forms\Components\Select::make('contact_person_id')
                        ->label('Contact Persoon')
                        ->relationship('contactPerson', 'name')
                        ->preload()
                        ->searchable(['name']),

                    Forms\Components\Select::make('neighbourhood_id')
                        ->label('Wijk')
                        ->relationship('neighbourhood', 'name')
                        ->preload()
                        ->searchable(['name']),
                ])->columns(),

                Forms\Components\Section::make('Opmerkingen')->schema([
                    Forms\Components\Textarea::make('comment')
                        ->label('')
                        ->columnSpanFull()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Activiteit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project'),
                Tables\Columns\TextColumn::make('task.name')
                    ->label('Taak'),
                Tables\Columns\TextColumn::make('neighbourhood.name')
                    ->label('Wijk'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project.name')
                    ->relationship('project', 'name')
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('neighbourhood.name')
                    ->label('Wijk')
                    ->relationship('neighbourhood', 'name')
                    ->multiple()
                    ->preload()
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
