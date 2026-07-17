<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    protected $fillable = [
        'alumni_id',
        'status_kerja',
        'waktu_tunggu',
        'kesesuaian_bidang',
        'tingkat_tempat_kerja',
        'nama_perusahaan',
        'jabatan',
        'pendapatan_pertama',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}
