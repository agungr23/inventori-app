<?php

namespace App\Filament\Resources\HargaPembeliResource\Pages;

use App\Filament\Resources\HargaPembeliResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHargaPembelis extends ListRecords
{
    protected static string $resource = HargaPembeliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
