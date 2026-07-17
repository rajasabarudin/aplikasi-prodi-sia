<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiDosen extends Model
{
    use HasFactory;

    protected $table = 'prestasi_dosens';

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'nama_prestasi',
        'tahun',
        'ts_id',
        'penyelenggara',
        'level_prestasi',
        'prestasi_diraih',
        'link_dokumen',
        'hibah_penelitian_id',
        'kategori_tridharma',
    ];

    public function ts()
    {
        return $this->belongsTo(Ts::class, 'ts_id');
    }

    public function hibahPenelitian()
    {
        return $this->belongsTo(HibahPenelitian::class, 'hibah_penelitian_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'kode_dosen', 'kode_dosen');
    }

    public function rekognisiDosen()
    {
        return $this->hasMany(RekognisiDosen::class, 'prestasi_dosen_id');
    }
}
