<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rps extends Model
{
    use HasFactory;
    
    protected $table = 'rps';
    protected $fillable = [
        'kode_matakuliah',
        'nomor_dokumen',
        'tanggal_penyusunan',
        'dosen_pengembang',
        'koordinator',
        'kaprodi',
        'deskripsi_singkat',
        'bobot_kehadiran',
        'bobot_tugas',
        'bobot_project',
        'asesmen_tertulis',
        'asesmen_lisan',
        'asesmen_kinerja',
        'asesmen_portofolio',
    ];

    protected $casts = [
        'asesmen_tertulis' => 'boolean',
        'asesmen_lisan' => 'boolean',
        'asesmen_kinerja' => 'boolean',
        'asesmen_portofolio' => 'boolean',
        'tanggal_penyusunan' => 'date',
    ];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_matakuliah', 'kode_matakuliah');
    }

    public function pertemuans()
    {
        return $this->hasMany(RpsPertemuan::class, 'rps_id');
    }

    public function rtm()
    {
        return $this->hasOne(Rtm::class, 'rps_id');
    }

    public function silabus()
    {
        return $this->hasOne(Silabus::class, 'rps_id');
    }

    public function penelitians()
    {
        return $this->belongsToMany(PenelitianDosen::class, 'rps_penelitian', 'rps_id', 'penelitian_dosen_id')
            ->withPivot('bentuk_integrasi')
            ->withTimestamps();
    }

    public function pkms()
    {
        return $this->belongsToMany(PKMDosen::class, 'rps_pkm', 'rps_id', 'pkm_dosen_id')
            ->withPivot('bentuk_integrasi')
            ->withTimestamps();
    }

    public function nilaiMentahs()
    {
        return $this->hasMany(NilaiMentahMahasiswa::class, 'rps_id');
    }
}
