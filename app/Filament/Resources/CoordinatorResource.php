<?php

namespace App\Filament\Resources;

use App\Enums\Coordinator\Role;
use App\Enums\Workday\Workday;
use App\Filament\Resources\UserResource\Pages;
use App\Models\Coordinator;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoordinatorResource extends Resource
{
    protected static ?string $model = Coordinator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Coördinatoren';

    protected static ?string $navigationGroup = 'Contacten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Werknemer')
                    ->columns()
                    ->schema([
                        TextInput::make('name')
                            ->name('Naam')
                            ->required()
                            ->ascii()
                            ->maxLength(255),
                        Select::make('role')
                            ->name('Gebruikersrol')
                            ->required()
                            ->enum(Role::class)
                            ->options(Role::class),
                        TextInput::make('email')
                            ->name('Email')
                            ->required()
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->name('Telefoonnummer')
                            ->tel()
                    ]),
                Section::make('Wijken')
                    ->schema([
                        CheckboxList::make('neighbourhood_id')
                            ->label('')
                            ->relationship('neighbourhoods', 'name', fn($query) => $query->whereNot('name', 'Alle wijken'))
                            ->columns()
                    ])->columnSpan(1),
                Section::make('Werkdagen')
                    ->schema([
                        CheckboxList::make('workdays')
                            ->name('')
                            ->columns()
                            ->options(Workday::toArray())
                    ])->columnSpan(1)
            ])->columns();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('role'),
                TextColumn::make('phone')
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
            ->schema([
                \Filament\Infolists\Components\Section::make('')
                    ->schema([
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('phone')
                            ->label('Telefoonnummer'),
                    ])->columnSpan(1),
                \Filament\Infolists\Components\Section::make('')
                    ->schema([
                        TextEntry::make('neighbourhoods.name')
                            ->label('Wijken'),
                        TextEntry::make('workdays')
                            ->label('Werkdagen')
                            ->formatStateUsing(fn(string $state) => ucfirst(strtolower($state))),
                    ])->columnSpan(1)

            ])
            ->columns();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoordinator::route('/'),
            'create' => Pages\CreateCoordinator::route('/create'),
            'view' => Pages\ViewCoordinator::route('/{record}'),
            'edit' => Pages\EditCoordinator::route('/{record}/edit'),
        ];
    }
}
