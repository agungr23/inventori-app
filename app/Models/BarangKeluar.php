<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $fillable = [
        'id_transaksi',
        'no_po',
        'tanggal_po',
        'tanggal_kirim',
        'barang_id',
        'pembeli_id',
        'jumlah',
        'harga_total',
    ];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class);
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class);
    }

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    protected static function booted()
    {
        static::updating(function ($barangKeluar) {
            $originalJumlah = $barangKeluar->getOriginal('jumlah');
            $newJumlah = $barangKeluar->jumlah;
            $selisih = $originalJumlah - $newJumlah;

            if ($selisih !== 0) {
                $barang = $barangKeluar->barang;
                if ($barang) {
                    $barang->stok += $selisih;
                    $barang->save();
                }
            }
        });
    }
}