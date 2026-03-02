<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSppd extends Model
{
    protected $table = 'data_sppd';

    protected $fillable = [
        'user_id',
        'st',
        'kota',
        'deskripsi',
        'angkutan',
        'tg_berangkat',
        'tg_pulang',
        'file_st',
    ];

    protected function casts(): array
    {
        return [
            'file_st' => 'array',
            'tg_berangkat' => 'date',
            'tg_pulang' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
