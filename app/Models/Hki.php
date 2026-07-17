<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hki extends Model
{
    use HasFactory;

    protected $table = 'hkis';

    protected $fillable = [
        'nim',
        'no_permohonan',
        'tgl_permohonan',
        'jenis_ciptaan',
        'judul_ciptaan',
        'kode_dosen',
        'nama_dosen',
        'link_dokumen',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function rekognisiDosen()
    {
        return $this->hasMany(RekognisiDosen::class, 'hki_id');
    }
}
