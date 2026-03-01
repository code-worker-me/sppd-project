<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPerjalanan extends Model
{
    protected $table = 'data_perjalanan';

    protected $fillable = [
        'sppd_id',
        'tiket_pergi',
        'tiket_pulang',
        'hotel',
        'uang_harian',
        'uang_representasi',
        'transport_lokal_pergi',
        'transport_lokal_pulang',
        'bbm_tol',
        'jumlah_sppd',
        'saldo_umum',
        'saldo_pengembangan',
        'panjar_kerja',
        'ketarangan',
        'lampiran'
    ];

    protected $casts = [
        'lampiran' => 'array',
        'tiket_pergi' => 'integer',
        'tiket_pulang' => 'integer',
        'hotel' => 'integer',
        'uang_harian' => 'integer',
        'uang_representasi' => 'integer',
        'transport_lokal_pergi' => 'integer',
        'transport_lokal_pulang' => 'integer',
        'bbm_tol' => 'integer',
        'jumlah_sppd' => 'integer',
        'saldo_umum' => 'integer',
        'saldo_pengembangan' => 'integer',
        'panjar_kerja' => 'integer',
        'keterangan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sppd()
    {
        return $this->belongsTo(DataSppd::class, 'sppd_id');
    }
}
