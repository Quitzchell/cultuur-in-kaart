<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Samenwerkingspartners';

    protected static ?string $navigationGroup = 'Contacten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('neighbourhood_id')
                    ->label('Wijk')
                    ->relationship('neighbourhood', 'name')
                    ->required(),
                Forms\Components\TextInput::make('street')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('house_number')
                    ->required()
                    ->numeric()
                    ->maxLength(10),
                Forms\Components\TextInput::make('house_number_addition')
                    ->maxLength(10),
                Forms\Components\Select::make('contact_person_id')
                    ->label('Contactpersoon')
                    ->relationship('contactPerson', 'name')
                    ->searchable(['name'])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('street')
                    ->searchable(),
                Tables\Columns\TextColumn::make('house_number')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(''),
                Tables\Actions\EditAction::make()
                    ->label(''),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
                            ->schema([
                                TextEntry::make('street')
                                    ->label('Adres')
                                    ->inlineLabel()
                                    ->formatStateUsing(function (string $state, ?Partner $record) {
                                        return implode(' ', [$state, implode('', [$record->house_number, ucfirst($record?->house_number_addition)])]);
                                    }),
                                TextEntry::make('zip')
                                    ->label('Postcode')
                                    ->inlineLabel(),
                                TextEntry::make('city')
                                    ->label('Stad')
                                    ->inlineLabel(),
                                TextEntry::make('Neighbourhood.name')
                                    ->label('Wijk')
                                    ->inlineLabel(),
                            ])
                    ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'view' => Pages\ViewPartner::route('/{record}'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
