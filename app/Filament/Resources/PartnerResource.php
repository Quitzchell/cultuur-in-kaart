<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section as infoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Samenwerkingspartners';
    protected static ?string $navigationGroup = 'Contacten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Algemeen')
                    ->columns(['default' => 1, 'lg' => 4])
                    ->schema([
                        TextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 2, 'lg' => 4]),
                        TextInput::make('street')
                            ->label('Straat')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 2, 'lg' => 2]),
                        TextInput::make('house_number')
                            ->label('Huisnummer')
                            ->required()
                            ->numeric()
                            ->maxLength(10)
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                        TextInput::make('house_number_addition')
                            ->label('Huisnummertoevoeging')
                            ->maxLength(10)
                            ->live(true)
                            ->afterStateUpdated(function (TextInput $component, ?string $state) {
                                isset($state) && $component->state(strtoupper($state));
                            }),
                        TextInput::make('zip')
                            ->label('Postcode')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                        TextInput::make('city')
                            ->label('Stad')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                        Forms\Components\Select::make('neighbourhood_id')
                            ->label('Wijk')
                            ->relationship('neighbourhood', 'name')
                            ->columnSpan(2),
                    ]),

//                Forms\Components\Section::make('Contactpersonen')
//                    ->columns(['default' => 1, 'lg' => 2])
//                    ->schema([
//                        Forms\Components\Select::make('contact_person_id')
//                            ->createOptionForm(ContactPersonModalForm::getForm())
//                            ->label('Contactpersonen')
//                            ->relationship('contactPeople', 'name')
//                            ->live()
//                            ->nullable()
//                            ->preload()
//                            ->multiple()
//                            ->searchable(['name']),
//                        Forms\Components\Select::make('primary_contact_person_id')
//                            ->label('Primair contactpersoon')
//                            ->relationship(
//                                'primaryContactPerson',
//                                'name',
//                                function (Builder $query, Get $get) {
//                                    return $query->whereIn('id', $get('contact_person_id'));
//                                })
//                            ->nullable()
//                            ->preload()
//                            ->searchable(['name']),
//                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Adres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('neighbourhood.name')
                    ->label('Wijk')
                    ->placeholder('-')
                    ->searchable()
                    ->limit(40),
            ])
            ->filters([
                SelectFilter::make('neighbourhood_id')
                    ->relationship('neighbourhood', 'name')
                    ->label('Wijk')
                    ->preload()
                    ->multiple(),
            ], layout: FiltersLayout::Modal)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(''),
                Tables\Actions\EditAction::make()
                    ->label(''),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(['default' => 1, 'lg' => 2])
            ->schema([
                Grid::make()
                    ->columnSpan(1)
                    ->schema([
                        infoSection::make('')
                            ->schema([
                                TextEntry::make('street')
                                    ->label('Adres')
                                    ->inlineLabel()
                                    ->formatStateUsing(function (string $state, ?Partner $record) {
                                        return implode(' ', [$state, implode('', [$record->house_number, ucfirst($record?->house_number_addition)])]);
                                    }),
                                TextEntry::make('zip')
                                    ->label('Postcode')
                                    ->inlineLabel()->formatStateUsing(function (string $state, ?Partner $record) {
                                        return "{$state} {$record->city}";
                                    }),
                                TextEntry::make('Neighbourhood.name')
                                    ->label('Wijk')
                                    ->inlineLabel(),
                            ])
                    ]),

//                Grid::make()
//                    ->columnSpan(1)
//                    ->schema([
//                        InfoSection::make('')->schema([
//                            TextEntry::make('contactPeople.name')
//                                ->label('contactpersonen')
//                                ->inlineLabel()
//                                ->placeholder('-'),
//                            TextEntry::make('primaryContactPerson.name')
//                                ->label('Primair contactpersoon')
//                                ->inlineLabel()
//                                ->placeholder('-'),
//                        ])
//                    ])
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'view' => Pages\ViewPartner::route('/{record}'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
