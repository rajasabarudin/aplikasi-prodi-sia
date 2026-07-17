<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiKepuasan extends Model
{
    use HasFactory;
    
    protected $table = 'survei_kepuasans';

    protected $fillable = [
        'tahun_akademik',
        'jenis_survei',
        'sangat_baik',
        'baik',
        'cukup',
        'kurang',
        'tindak_lanjut',
    ];
}
