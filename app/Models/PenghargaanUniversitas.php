<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenghargaanUniversitas extends Model
{
    use HasFactory;

    protected $table = 'penghargaan_universitas';
    
    protected $fillable = [
        'tahun',
        'nama_mitra',
        'nomor_penghargaan',
        'link_dokumen_penghargaan',
        'link_berita',
    ];
}
