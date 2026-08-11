<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_mahasiswas';

    protected $fillable = [
        'nim',
        'nama_kegiatan',
        'jenis_kegiatan',
        'tgl_kegiatan',
        'penyelenggara',
        'link_bukti_kegiatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
