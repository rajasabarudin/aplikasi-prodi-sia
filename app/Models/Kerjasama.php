<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kerjasama extends Model
{
    use HasFactory;

    protected $table = 'kerjasama';

    protected $fillable = [
        'tahun_mou',
        'tahun_berakhir',
        'nomor_mou_ubsi',
        'nomor_mou_mitra',
        'nama_mitra',
        'ketua_mewakili',
        'no_wa_mitra',
        'file_mou',
    ];
}
