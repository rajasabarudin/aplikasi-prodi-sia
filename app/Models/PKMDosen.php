<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PKMDosen extends Model
{
    use HasFactory;

    protected $table = 'pkm_dosens';

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'tema_pkm',
        'mitra',
        'jenis_pkm',
        'biaya',
        'sumber_iptek',
        'nim_mhs',
        'nama_mahasiswa',
        'ts_id',
        'link_dokumen',
        'link_publikasi',
    ];

    public function ts()
    {
        return $this->belongsTo(TS::class, 'ts_id');
    }
}
