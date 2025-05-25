<?php

namespace App\Filament\Resources\BarangMasukResource\Pages;

use App\Filament\Resources\BarangMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarangMasuk extends CreateRecord
{
    protected static string $resource = BarangMasukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id_transaksi'] = $this->generateIdTransaksi();
        return $data;
    }

    protected function generateIdTransaksi(): string
    {
        $last = \App\Models\BarangMasuk::latest('id')->first();
        $number = $last ? ((int) substr($last->id_transaksi, 3)) + 1 : 1;
        return 'TM-' . str_pad($number, 7, '0', STR_PAD_LEFT);
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $barang = $record->barang;
        if ($barang) {
            $barang->increment('stok', $record->jumlah);
        }
    }
}