<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenelitianDosen extends Model
{
    use HasFactory;

    protected $table = 'penelitian_dosens';

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'jenis_jurnal',
        'jenis_penelitian',
        'biaya',
        'nama_jurnal',
        'link_jurnal',
        'berkas_sertifikat',
        'berkas_paper',
        'proposal',
        'laporan',
        'lainnya',
        'ts_id',
        'nim_mhs',
        'nama_mahasiswa',
        'anggota_mitra',
    ];

    public function ts()
    {
        return $this->belongsTo(Ts::class, 'ts_id');
    }

    public function rekognisiDosen()
    {
        return $this->hasMany(RekognisiDosen::class, 'penelitian_dosen_id');
    }
}
