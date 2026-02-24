<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
    ];

    protected $casts = [
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

    public function getTotalBiayaAttribute(): int
    {
        return $this->uang_saku + $this->transport;
    }

    public static function getTotalSemuaBiaya(): int
    {
        return self::all()->sum(function ($perjalanan) {
            return $perjalanan->total_biaya;
        });
    }

    protected static function booted()
    {
        static::deleting(function ($perjalanan) {
            if ($perjalanan->tiket && Storage::disk('public')->exists($perjalanan->tiket)) {
                Storage::disk('public')->delete($perjalanan->tiket);
            }
            if ($perjalanan->hotel && Storage::disk('public')->exists($perjalanan->hotel)) {
                Storage::disk('public')->delete($perjalanan->hotel);
            }
        });
    }
}
