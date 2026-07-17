<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RpsReferensi extends Model
{
    use HasFactory;

    protected $table = 'rps_referensis';

    protected $fillable = ['kode_matakuliah', 'jenis', 'penulis', 'tahun', 'judul', 'penerbit', 'kota', 'url'];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_matakuliah', 'kode_matakuliah');
    }
}
