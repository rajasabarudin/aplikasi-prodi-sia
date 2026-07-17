<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaDropOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_masuk',
        'jumlah_do',
    ];
}
