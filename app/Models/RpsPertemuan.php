<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RpsPertemuan extends Model
{
    use HasFactory;
    
    protected $table = 'rps_pertemuans';
    protected $fillable = [
        'rps_id',
        'minggu_ke',
        'sub_cpmk',
        'bahan_kajian',
        'metode_pembelajaran',
        'waktu_pembelajaran',
        'pengalaman_belajar',
        'kriteria_penilaian',
        'indikator_penilaian',
        'bobot_penilaian',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class, 'rps_id');
    }
}
