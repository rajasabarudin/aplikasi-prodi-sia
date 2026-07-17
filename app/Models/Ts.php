<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ts extends Model
{
    use HasFactory;

    protected $table = 'ts';

    protected $fillable = [
        'tahun_sekarang',
        'semester',
        'label_ts',
    ];
}
