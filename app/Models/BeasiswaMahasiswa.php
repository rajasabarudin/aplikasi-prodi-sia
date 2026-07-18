<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeasiswaMahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'jenis_beasiswa',
        'kategori_beasiswa',
        'link_dokumen',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
