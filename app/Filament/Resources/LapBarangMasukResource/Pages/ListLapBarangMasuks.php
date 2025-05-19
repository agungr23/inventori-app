<?php

namespace App\Filament\Resources\LapBarangMasukResource\Pages;

use App\Filament\Resources\LapBarangMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLapBarangMasuks extends ListRecords
{
    protected static string $resource = LapBarangMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
