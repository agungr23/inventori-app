<?php

namespace App\Filament\Resources\DataBarangResource\Pages;

use App\Filament\Resources\DataBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataBarang extends EditRecord
{
    protected static string $resource = DataBarangResource::class;

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