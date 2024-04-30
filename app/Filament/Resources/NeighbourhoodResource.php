<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NeighbourhoodResource\Pages;
use App\Filament\Resources\NeighbourhoodResource\RelationManagers;
use App\Models\Neighbourhood;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NeighbourhoodResource extends Resource
{
    protected static ?string $model = Neighbourhood::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Wijken';

    protected static ?string $navigationGroup = 'Overig';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(''),
                Tables\Actions\DeleteAction::make()
                    ->label(''),
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
            'index' => Pages\ListNeighbourhoods::route('/'),
            'create' => Pages\CreateNeighbourhood::route('/create'),
            'edit' => Pages\EditNeighbourhood::route('/{record}/edit'),
        ];
    }
}
