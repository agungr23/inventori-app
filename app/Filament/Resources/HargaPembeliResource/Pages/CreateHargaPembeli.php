<?php

namespace App\Filament\Resources\HargaPembeliResource\Pages;

use App\Filament\Resources\HargaPembeliResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHargaPembeli extends CreateRecord
{
    protected static string $resource = HargaPembeliResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}