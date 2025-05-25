<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = [
        'id_transaksi',
        'tanggal_masuk',
        'barang_id',
        'jumlah',
    ];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class);
    }
}