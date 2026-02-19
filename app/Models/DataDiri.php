<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDiri extends Model
{
    protected $table = 'data_diri';

    protected $fillable = [
        'user_id',
        'nip',
        'unit_kerja',
        'pangkat',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
