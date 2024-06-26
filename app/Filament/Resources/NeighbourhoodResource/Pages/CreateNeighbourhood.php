<?php

namespace App\Filament\Resources\NeighbourhoodResource\Pages;

use App\Filament\Resources\NeighbourhoodResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNeighbourhood extends CreateRecord
{
    protected static string $resource = NeighbourhoodResource::class;

    protected static ?string $title = 'Wijk aanmaken';
}
