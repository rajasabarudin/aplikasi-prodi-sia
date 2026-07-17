<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class PesertaKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kegiatan_id',
        'nama',
        'identifier',
        'kategori',
        'barcode_token',
        'jam_masuk',
        'jam_pulang',
        'status_kehadiran',
        'status_pembayaran',
        'bukti_pembayaran',
        'foto',
    ];

    protected $casts = [
        'jam_masuk' => 'datetime',
        'jam_pulang' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->barcode_token)) {
                $model->barcode_token = 'EVT-' . Str::upper(Str::random(8)) . '-' . mt_rand(100, 999);
            }
        });
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}
