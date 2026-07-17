<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganProdi extends Model
{
    use HasFactory;
    
    protected $table = 'keuangan_prodis';

    protected $fillable = [
        'tahun_akademik',
        'jumlah_mahasiswa_aktif',
        'dana_pendidikan',
        'dana_penelitian',
        'dana_pkm',
        'dana_investasi',
    ];
}
