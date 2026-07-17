<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanDosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'nama_kegiatan',
        'tahun',
        'ts_id',
        'penyelenggara',
        'jenis',
        'link_dokumen',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }

    public function ts()
    {
        return $this->belongsTo(TS::class, 'ts_id', 'id');
    }
}
