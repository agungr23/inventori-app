<?php

namespace App\Filament\Resources\HargaPembeliResource\Pages;

use App\Filament\Resources\HargaPembeliResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHargaPembeli extends EditRecord
{
    protected static string $resource = HargaPembeliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}