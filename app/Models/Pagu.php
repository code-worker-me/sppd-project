<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagu extends Model
{
    protected $fillable = [
        'tahun_anggaran',
        'saldo_umum',
        'saldo_pu'
    ];
}
