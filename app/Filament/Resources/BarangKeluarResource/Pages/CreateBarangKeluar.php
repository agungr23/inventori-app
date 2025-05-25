<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateBarangKeluar extends CreateRecord
{
    protected static string $resource = BarangKeluarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['id_transaksi'])) {
            $data['id_transaksi'] = BarangKeluarResource::generateIdTransaksi();
        }
        if (empty($data['no_po'])) {
            $data['no_po'] = BarangKeluarResource::generateNoPO();
        }

        $data['harga_total'] = ($data['harga_satuan'] ?? 0) * ($data['jumlah'] ?? 0);
        Log::info('CreateBarangKeluar data: ', $data);
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $barang = $record->barang;

        if ($barang) {
            $barang->decrement('stok', $record->jumlah);
        }
    }
}