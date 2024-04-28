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
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                Forms\Components\Section::make('Algemeen')
                    ->columns(['default' => 1, 'lg' => 4])
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 2, 'lg' => 4]),
                        Forms\Components\TextInput::make('street')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 2, 'lg' => 2]),
                        Forms\Components\TextInput::make('house_number')
                            ->required()
                            ->numeric()
                            ->maxLength(10)
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                        Forms\Components\TextInput::make('house_number_addition')
                            ->maxLength(10)
                            ->live(true)
                            ->afterStateUpdated(function (TextInput $component, ?string $state) {
                                isset($state) && $component->state(strtoupper($state));
                            }),
                        Forms\Components\TextInput::make('zip')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['default' => 1, 'lg' => 1]),
                        Forms\Components\Select::make('neighbourhood_id')
                            ->label('Wijk')
                            ->relationship('neighbourhood', 'name')
                            ->required()
                            ->columnSpan(2),
                    ]),

                Forms\Components\Section::make('Contactpersonen')
                    ->columns(['default' => 1, 'lg' => 2])
                    ->schema([
                        Forms\Components\Select::make('contactPeople')
                            ->label('Contactpersonen')
                            ->relationship('contactPeople', 'name')
                            ->live()
                            ->nullable()
                            ->preload()
                            ->multiple()
                            ->searchable(['name']),
                        Forms\Components\Select::make('contact_person_id')
                            ->label('Primair contactpersoon')
                            ->relationship(
                                'contactPerson',
                                'name',
                                function (Builder $query, Get $get) {
                                    return $query->whereIn('id', $get('contactPeople'));
                                })
                            ->disabled(fn(Get $get) => empty($get('contactPeople')))
                            ->nullable()
                            ->preload()
                            ->searchable(['name']),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Adres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('neighbourhoods.name')
                    ->label('Wijken')
                    ->default('-')
                    ->limit(40),
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
            RelationManagers\ActivityRelationManager::make(),
            RelationManagers\ProjectRelationManager::make()
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
                                    ->inlineLabel()->formatStateUsing(function (string $state, ?Partner $record) {
                                        return "{$state} {$record->city}";
                                    }),
                                TextEntry::make('Neighbourhood.name')
                                    ->label('Wijk')
                                    ->inlineLabel(),
                            ])
                    ]),
                Grid::make()
                    ->columnSpan(1)
                    ->schema([
                        InfoSection::make('')->schema([
                            TextEntry::make('contactPeople.name')
                                ->label('contactpersonen')
                                ->inlineLabel()
                                ->default('-'),
                            TextEntry::make('contactPerson.name')
                                ->label('Primair contactpersoon')
                                ->inlineLabel()
                                ->default('-'),
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
