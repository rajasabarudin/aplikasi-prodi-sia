<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiMentahMahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'rps_id', 'kelas_id', 'mahasiswa_id', 'tahun_ajaran',
        'nilai_kehadiran', 'nilai_tugas', 'nilai_project', 'nilai_praktek',
        'nilai_kuis', 'nilai_uts', 'nilai_uas', 'nilai_presentasi', 'nilai_akhir'
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
}
