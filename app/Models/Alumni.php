<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $fillable = [
        'nim',
        'nama',
        'tahun_masuk',
        'tahun_lulus',
        'ipk',
        'no_telepon',
        'email',
    ];

    public function tracerStudy()
    {
        return $this->hasOne(TracerStudy::class);
    }
}
