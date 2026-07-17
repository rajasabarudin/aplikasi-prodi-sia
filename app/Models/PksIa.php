<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PksIa extends Model
{
    use HasFactory;

    protected $table = 'pks_ia';

    protected $fillable = [
        'nama_mitra',
        'tgl_pks',
        'tgl_ia',
        'no_pks_ubsi',
        'no_pks_mitra',
        'no_ia_ubsi',
        'no_ia_mitra',
        'tema_pks',
        'judul_ia',
        'kategori',
        'level_pks',
        'file_pks',
        'file_ia',
        'file_tambahan',
    ];

    protected $casts = [
        'tgl_pks' => 'date',
        'tgl_ia' => 'date',
    ];
}
