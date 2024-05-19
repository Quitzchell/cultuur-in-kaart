<?php

namespace App\Filament\Resources;

use App\Enums\Coordinator\Role;
use App\Enums\Workday\Workday;
use App\Filament\Resources\CoordinatorResource\Pages;
use App\Filament\Resources\CoordinatorResource\RelationManagers;
use App\Models\Coordinator;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoordinatorResource extends Resource
{
    protected static ?string $model = Coordinator::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Coördinatoren';
    protected static ?string $navigationGroup = 'Overig';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Werknemer')
                    ->columns()
                    ->schema([
                        TextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->maxLength(255),
                        Select::make('role')
                            ->label('Gebruikersrol')
                            ->required()
                            ->enum(Role::class)
                            ->options(Role::class),
                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Wachtwoord')
                            ->hiddenOn('edit')
                            ->required(),
                        TextInput::make('phone')
                            ->label('Telefoonnummer')
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
            ->defaultSort('name')
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('')
                    ->schema([
                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-')
                            ->inlineLabel(),
                        TextEntry::make('phone')
                            ->label('Telefoonnummer')
                            ->placeholder('-')
                            ->inlineLabel(),
                        TextEntry::make('workdays')
                            ->label('Werkdagen')
                            ->formatStateUsing(fn(string $state) => ucfirst(strtolower($state)))
                            ->inlineLabel()
                            ->placeholder('-')
                    ])->columnSpan(1),
                \Filament\Infolists\Components\Section::make('')
                    ->schema([
                        TextEntry::make('neighbourhoods.name')
                            ->label('Wijken')
                            ->distinctList()
                            ->inlineLabel()
                            ->placeholder('-')
                    ])->columnSpan(1)
            ])->columns();
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
            'index' => Pages\ListCoordinators::route('/'),
            'create' => Pages\CreateCoordinator::route('/create'),
            'view' => Pages\ViewCoordinator::route('/{record}'),
            'edit' => Pages\EditCoordinator::route('/{record}/edit'),
        ];
    }
}
