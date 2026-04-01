<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagu extends Model
{
    protected $fillable = [
        'pagu_awal_umum',  // ✅ tambah
        'pagu_awal_pu',    // ✅ tambah
        'saldo_umum',
        'saldo_pu',
        'tahun_anggaran'
    ];
}
