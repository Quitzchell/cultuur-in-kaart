<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisciplineResource\Pages;
use App\Models\Discipline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DisciplineResource extends Resource
{
    protected static ?string $model = Discipline::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Disciplines';
    protected static ?string $navigationGroup = 'Overig';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Naam')
                        ->required()
                        ->maxLength(255)
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                ->label('Naam')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(''),
                Tables\Actions\DeleteAction::make()
                    ->label(''),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDisciplines::route('/'),
            'create' => Pages\CreateDiscipline::route('/create'),
            'edit' => Pages\EditDiscipline::route('/{record}/edit'),
        ];
    }
}
