<?php

namespace App\Filament\Resources\LapBarangKeluarResource\Pages;

use App\Filament\Resources\LapBarangKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLapBarangKeluar extends EditRecord
{
    protected static string $resource = LapBarangKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
