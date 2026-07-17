<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObeNilaiCpmk extends Model
{
    use HasFactory;

    protected $table = 'obe_nilai_cpmk';
    protected $fillable = [
        'rps_id',
        'kelas_id',
        'mahasiswa_id',
        'cpmk_id',
        'nilai',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class, 'rps_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function cpmk()
    {
        return $this->belongsTo(Cpmk::class, 'cpmk_id');
    }
}
