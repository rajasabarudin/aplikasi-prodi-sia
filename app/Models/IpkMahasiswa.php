<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpkMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'ipk_mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'ipk',
        'ts_id',
    ];

    public function ts()
    {
        return $this->belongsTo(TS::class, 'ts_id');
    }
}
