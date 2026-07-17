<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pmb extends Model
{
    use HasFactory;

    protected $table = 'pmb';

    protected $fillable = [
        'tahun',
        'jumlah_pmb',
    ];
}
