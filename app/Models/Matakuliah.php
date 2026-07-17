<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_matakuliah',
        'nama_matakuliah',
        'sks',
        'sks_t',
        'sks_pa',
        'sks_pu',
        'jenis_matakuliah',
        'semester',
        'link_modul',
        'link_rps',
        'link_rtm',
        'dokumen_tambahan',
    ];

    public function capstone()
    {
        return $this->belongsToMany(CapstoneMahasiswa::class, 'capstone_matakuliah', 'matakuliah_id', 'capstone_mahasiswa_id');
    }

    public function rps()
    {
        return $this->hasOne(Rps::class, 'kode_matakuliah', 'kode_matakuliah');
    }
}
