<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapstoneMahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama_mahasiswa',
        'judul_capstone',
        'link_dokumen',
        'anggota_kelompok',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function matakuliah()
    {
        return $this->belongsToMany(Matakuliah::class, 'capstone_matakuliah', 'capstone_mahasiswa_id', 'matakuliah_id');
    }
}
