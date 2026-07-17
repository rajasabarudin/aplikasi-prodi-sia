<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RpsBahanKajian extends Model
{
    use HasFactory;

    protected $table = 'rps_bahan_kajians';

    protected $fillable = ['kode_matakuliah', 'urutan', 'topik', 'sub_topik'];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_matakuliah', 'kode_matakuliah');
    }
}
