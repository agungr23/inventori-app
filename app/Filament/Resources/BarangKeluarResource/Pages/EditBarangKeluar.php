<?php

namespace App\Filament\Resources\BarangKeluarResource\Pages;

use App\Filament\Resources\BarangKeluarResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditBarangKeluar extends EditRecord
{
    protected static string $resource = BarangKeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected $oldJumlah;

    protected function fillForm(): void
    {
        parent::fillForm();

        $barangId = $this->record->barang_id;
        $pembeliId = $this->record->pembeli_id;

        $hargaSatuan = 0;
        if ($barangId && $pembeliId) {
            $hargaPembeli = \App\Models\HargaPembeli::where('barang_id', $barangId)
                ->where('pembeli_id', $pembeliId)
                ->first();

            $hargaSatuan = $hargaPembeli ? $hargaPembeli->harga_jual : 0;
        }

        $this->form->fill([
            'id_transaksi' => $this->record->id_transaksi,
            'no_po' => $this->record->no_po,
            'tanggal_po' => $this->record->tanggal_po,
            'tanggal_kirim' => $this->record->tanggal_kirim,
            'harga_satuan' => $hargaSatuan,  // ambil dari tabel harga pembeli
            'jumlah' => $this->record->jumlah,
            'harga_total' => $this->record->harga_total,
        ]);
    }

    protected function beforeSave(): void
    {
        $this->oldJumlah = $this->record->jumlah;

        $data = $this->form->getState();

        $barang = $this->record->barang;

        if (!$barang) {
            Notification::make()
                ->title('Data barang tidak ditemukan')
                ->danger()
                ->send();

            $this->halt(); // Batalkan simpan
            return;
        }

        $selisih = $this->oldJumlah - $data['jumlah']; // Perubahan jumlah

        $stokBaru = $barang->stok + $selisih; // Hitung stok baru

        if ($stokBaru < 0) {
            Notification::make()
                ->title('Stok tidak boleh kurang dari 0')
                ->danger()
                ->send();

            $this->halt(); // Batalkan simpan
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['harga_total'] = ($data['harga_satuan'] ?? 0) * ($data['jumlah'] ?? 0);
        Log::info('EditBarangKeluar data: ', $data);
        return $data;
    }

    
}