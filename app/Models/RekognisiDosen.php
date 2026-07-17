<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekognisiDosen extends Model
{
    use HasFactory;

    protected $table = 'rekognisi_dosens';

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'nama_rekognisi',
        'tahun',
        'ts_id',
        'level',
        'link_dokumen',
        'is_keanggotaan',
        'kategori_tridharma',
        'penelitian_dosen_id',
        'hibah_penelitian_id',
        'hki_id',
        'prestasi_dosen_id',
    ];

    public function ts()
    {
        return $this->belongsTo(Ts::class, 'ts_id');
    }

    public function penelitianDosen()
    {
        return $this->belongsTo(PenelitianDosen::class, 'penelitian_dosen_id');
    }

    public function hibahPenelitian()
    {
        return $this->belongsTo(HibahPenelitian::class, 'hibah_penelitian_id');
    }

    public function hki()
    {
        return $this->belongsTo(Hki::class, 'hki_id');
    }

    public function prestasiDosen()
    {
        return $this->belongsTo(PrestasiDosen::class, 'prestasi_dosen_id');
    }
}

