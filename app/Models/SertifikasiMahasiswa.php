<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikasiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'sertifikasi_mahasiswas';

    protected $fillable = [
        'nim',
        'nama_mhs',
        'skema_serkom',
        'link_dokumen',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
