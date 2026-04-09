<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSppd extends Model
{
    protected $table = 'data_sppd';

    protected $fillable = [
        'st',
        'kategori',
        'kota',
        'deskripsi',
        'angkutan',
        'tg_berangkat',
        'tg_pulang',
    ];

    protected function casts(): array
    {
        return [
            'tg_berangkat' => 'date',
            'tg_pulang' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'data_sppd_user', 'data_sppd_id', 'user_id');
    }

    public function lampiran()
    {
        return $this->hasOne(Lampiran::class, 'sppd_id');
    }

    public function perjalanan()
    {
        return $this->hasOne(DataPerjalanan::class, 'sppd_id');
    }

    public function getDurasiAttribute(): int
    {
        if ($this->tg_berangkat && $this->tg_pulang) {
            return $this->tg_berangkat->diffInDays($this->tg_pulang);
        }
        return 0;
    }
}
