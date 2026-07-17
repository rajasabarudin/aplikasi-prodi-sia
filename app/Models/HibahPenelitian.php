<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HibahPenelitian extends Model
{
    use HasFactory;

    protected $table = 'hibah_penelitians';

    protected $fillable = [
        'jenis_hibah',
        'kode_dosen',
        'nama_dosen',
        'nim_mhs',
        'nama_mahasiswa',
        'skema_hibah',
        'tema_hibah',
        'topik_hibah',
        'judul',
        'biaya',
        'link_proposal',
        'link_st',
        'link_spk',
        'link_luaran',
        'link_laporan',
        'link_persentasi',
        'ts_id',
        'pemberi_hibah',
    ];

    public function ts()
    {
        return $this->belongsTo(TS::class, 'ts_id');
    }

    public function rekognisiDosen()
    {
        return $this->hasMany(RekognisiDosen::class, 'hibah_penelitian_id');
    }

    public function prestasiDosen()
    {
        return $this->hasMany(PrestasiDosen::class, 'hibah_penelitian_id');
    }
}
