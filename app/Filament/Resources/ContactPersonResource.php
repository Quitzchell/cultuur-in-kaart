<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\ContactPersonResource\ActivityRelationManager;
use App\Filament\RelationManagers\ContactPersonResource\ProjectRelationManager;
use App\Filament\Resources\ContactPersonResource\Pages;
use App\Models\ContactPerson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfoSection;
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

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Contactpersonen';

    protected static ?string $modelLabel = 'Contactpersoon';

    protected static ?string $pluralModelLabel = 'Contactpersonen';

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
                            ->label('Samenwerkingspartners')
                            ->relationship('partners', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable('name'),
                        Forms\Components\Textarea::make('comment')
                            ->label('Opmerkingen')
                            ->columnSpanFull(),
                    ]),
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
                SelectFilter::make('partners')
                    ->relationship('partners', 'name')
                    ->label('Samenwerkingspartner')
                    ->preload()
                    ->multiple(),
            ], FiltersLayout::Modal)
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
                InfoSection::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Naam')
                            ->inlineLabel(),
                        TextEntry::make('email')
                            ->placeholder('-')
                            ->inlineLabel(),
                        TextEntry::make('phone')
                            ->label('Telefoonnummer')
                            ->placeholder('-')
                            ->inlineLabel(),
                        TextEntry::make('partners.name')
                            ->label('Samenwerkingspartner')
                            ->placeholder('-')
                            ->inlineLabel(),
                    ])->columnSpan(1),

                InfoSection::make()
                    ->schema([
                        TextEntry::make('comment')
                            ->label('Opmerkingen')
                            ->placeholder('-'),
                    ])->columnSpan(1),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivityRelationManager::class,
            ProjectRelationManager::class,
        ];
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
