<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactPersonResource\Pages;
use App\Filament\Resources\ContactPersonResource\RelationManagers;
use App\Models\ContactPerson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactPersonResource extends Resource
{
    protected static ?string $model = ContactPerson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Contactpersonen';

    protected static ?string $navigationGroup = 'Contacten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Algemeen')
                    ->columns()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Mailadres')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefoonnummer')
                            ->tel()
                            ->maxLength(38)
                            ->validationMessages(['regex' => 'Het telefoonnummer is ongeldig.']),
                        Forms\Components\Select::make('partner_id')
                            ->label('Samenwerkingspartner')
                            ->relationship('partners', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable('name'),
                        Forms\Components\Textarea::make('comment')
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefoonnummer'),
                TextColumn::make('partners.name')
                    ->label('Samenwerkingspartner')
                    ->placeholder('-')
                    ->searchable()
                    ->limit(40),
            ])
            ->filters([
                SelectFilter::make('partner_id')
                    ->relationship('partners', 'name')
                    ->label('Samenwerkingspartner')
                    ->preload()
                    ->multiple(),
            ], FiltersLayout::Modal)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(''),
                Tables\Actions\EditAction::make()
                    ->label('')
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
                Section::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Naam')
                            ->inlineLabel(),
                        TextEntry::make('email')
                            ->inlineLabel(),
                        TextEntry::make('phone')
                            ->label('Telefoonnummer')
                            ->inlineLabel(),
                        TextEntry::make('partners.name')
                            ->label('Samenwerkingspartner')
                            ->inlineLabel(),
                    ])->columnSpan(1),

                Section::make()
                    ->schema([
                        TextEntry::make('comment')
                            ->label('Opmerkingen')
                            ->default('-')
                            ->inlineLabel(),
                    ])->columnSpan(1)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactPeople::route('/'),
            'create' => Pages\CreateContactPerson::route('/create'),
            'view' => Pages\ViewContactPerson::route('/{record}'),
            'edit' => Pages\EditContactPerson::route('/{record}/edit'),
        ];
    }
}
