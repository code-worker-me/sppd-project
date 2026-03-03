<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    protected $table = 'lampiran';

    protected $fillable = [
        'sppd_id',
        'laporan_perjalanan',
        'foto_kegiatan',
        'blanko_sppd',
        'surat_tugas',
    ];

    protected function casts(): array
    {
        return [
            'laporan_perjalanan' => 'array',
            'foto_kegiatan' => 'array',
            'blanko_sppd' => 'array',
            'surat_tugas' => 'array',
        ];
    }

    public function sppd()
    {
        return $this->belongsTo(DataSppd::class);
    }
}
