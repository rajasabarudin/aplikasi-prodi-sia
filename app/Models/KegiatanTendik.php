<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanTendik extends Model
{
    protected $fillable = [
        'nip_nik',
        'nama_tendik',
        'nama_kegiatan',
        'tahun',
        'ts_id',
        'penyelenggara',
        'jenis',
        'kegiatan_prodi_id',
        'link_dokumen'
    ];

    public function tendik()
    {
        return $this->belongsTo(Tendik::class, 'nip_nik', 'nip_nik');
    }

    public function ts()
    {
        return $this->belongsTo(Ts::class, 'ts_id');
    }

    public function kegiatanSistem()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_prodi_id');
    }
}
