<?php

namespace App\Filament\Resources\LapBarangKeluarResource\Pages;

use App\Filament\Resources\LapBarangKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLapBarangKeluars extends ListRecords
{
    protected static string $resource = LapBarangKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
