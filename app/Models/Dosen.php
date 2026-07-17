<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'foto',
        'homebase_dosen',
        'nidn',
        'nuptk',
        'nip',
        'pendidikan',
        'gelar',
        'jfa',
        'kepangkatan',
        'keterangan_serdos',
        'jenjang',
        'kondisi_sisfo',
        'kondisi_pddikti',
    ];

    public function sertifikasi()
    {
        return $this->hasMany(SertifikasiDosen::class, 'kode_dosen', 'kode_dosen');
    }

    public function kegiatan()
    {
        return $this->hasMany(KegiatanDosen::class, 'kode_dosen', 'kode_dosen');
    }

    public function prestasi()
    {
        return $this->hasMany(PrestasiDosen::class, 'kode_dosen', 'kode_dosen');
    }

    public function rekognisi()
    {
        return $this->hasMany(RekognisiDosen::class, 'kode_dosen', 'kode_dosen');
    }
}
