<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikasiDosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'nama_sertifikasi',
        'penerbit',
        'link_dokumen',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }
}
