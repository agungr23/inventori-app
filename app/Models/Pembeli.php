<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'nomor_telepon',
        'email',
    ];
}