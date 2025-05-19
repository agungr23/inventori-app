<?php

namespace App\Filament\Resources\LapBarangMasukResource\Pages;

use App\Filament\Resources\LapBarangMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLapBarangMasuk extends EditRecord
{
    protected static string $resource = LapBarangMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
