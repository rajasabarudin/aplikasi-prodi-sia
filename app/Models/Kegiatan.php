<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'bidang_kegiatan',
        'tanggal',
        'tempat',
        'narasumber',
        'deskripsi',
        'tanda_tangan_nama',
        'tanda_tangan_jabatan',
        'jenis_kegiatan',
        'harga',
        'rekening_info',
        'tgl_pendaftaran_buka',
        'tgl_pendaftaran_tutup',
        'pin_masuk',
        'pin_pulang',
        'presensi_online_aktif',
    ];

    public function pesertas()
    {
        return $this->hasMany(PesertaKegiatan::class, 'kegiatan_id');
    }
}
