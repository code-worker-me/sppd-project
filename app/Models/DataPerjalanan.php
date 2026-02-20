<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DataPerjalanan extends Model
{
    protected $table = 'data_perjalanan';

    protected $fillable = [
        'sppd_id',
        'tipe_perjalanan',
        'tiket',
        'hotel',
        'uang_saku',
        'transport',
    ];

    protected $casts = [
        'uang_saku' => 'integer',
        'transport' => 'integer',
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
