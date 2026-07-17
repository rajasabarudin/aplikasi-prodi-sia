<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'prestasi_mahasiswas';

    protected $fillable = [
        'nim',
        'nama_prestasi',
        'tahun',
        'ts_id',
        'penyelenggara',
        'bidang_prestasi',
        'prestasi_diraih',
        'level_prestasi',
        'link_dokumen',
        'hibah_penelitian_id',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function ts()
    {
        return $this->belongsTo(TS::class, 'ts_id');
    }
}
