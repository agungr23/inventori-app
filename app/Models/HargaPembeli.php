<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaPembeli extends Model
{
    protected $fillable = [
        'barang_id',
        'harga_jual',
        'pembeli_id',
    ];

    public function barang()
    {
        return $this->belongsTo(DataBarang::class);
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class);
    }
}