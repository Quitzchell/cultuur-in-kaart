<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NeighbourhoodResource\Pages;
use App\Models\Neighbourhood;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NeighbourhoodResource extends Resource
{
    protected static ?string $model = Neighbourhood::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Wijken';

    protected static ?string $modelLabel = 'Wijk';

    protected static ?string $pluralModelLabel = 'Wijken';

    protected static ?string $navigationGroup = 'Overig';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Naam')
                        ->required()
                        ->maxLength(255),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam'),
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
            'index' => Pages\ListNeighbourhoods::route('/'),
            'create' => Pages\CreateNeighbourhood::route('/create'),
            'edit' => Pages\EditNeighbourhood::route('/{record}/edit'),
        ];
    }
}
