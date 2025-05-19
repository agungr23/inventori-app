<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataBarang extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'nama',
        'jenis_barang_id',
        'stok',
        'satuan_id',
    ];

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}