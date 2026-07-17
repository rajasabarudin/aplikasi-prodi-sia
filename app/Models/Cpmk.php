<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpmk extends Model
{
    use HasFactory;

    protected $table = 'cpmks';

    protected $fillable = ['kode_cpmk', 'deskripsi_cpmk', 'cpl_id', 'kode_matakuliah'];

    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'cpl_id');
    }

    public function matakuliahs()
    {
        return $this->belongsToMany(Matakuliah::class, 'cpmk_matakuliah', 'cpmk_id', 'kode_matakuliah', 'id', 'kode_matakuliah');
    }
}
