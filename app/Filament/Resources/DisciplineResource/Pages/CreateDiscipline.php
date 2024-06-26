<?php

namespace App\Filament\Resources\DisciplineResource\Pages;

use App\Filament\Resources\DisciplineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDiscipline extends CreateRecord
{
    protected static string $resource = DisciplineResource::class;

    protected static ?string $title = 'Discipline aanmaken';
}
