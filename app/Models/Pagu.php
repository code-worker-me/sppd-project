<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagu extends Model
{
    protected $fillable = [
        'saldo_umum',
        'saldo_pu',
        'tahun_anggaran'
    ];
}
