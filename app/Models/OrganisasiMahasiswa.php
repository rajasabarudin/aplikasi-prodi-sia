<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisasiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'organisasi_mahasiswas';

    protected $fillable = [
        'nim',
        'nama_organisasi',
        'jabatan',
        'periode',
        'ts_id',
        'link_sk',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function ts()
    {
        return $this->belongsTo(Ts::class, 'ts_id');
    }
}
