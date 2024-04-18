<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCoordinator extends CreateRecord
{
    protected static string $resource = CoordinatorResource::class;
}
