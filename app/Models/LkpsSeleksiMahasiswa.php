<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkpsSeleksiMahasiswa extends Model
{
    use HasFactory;
    
    protected $table = 'lkps_seleksi_mahasiswa';
    protected $guarded = [];

    public function ts()
    {
        return $this->belongsTo(Ts::class, 'ts_id');
    }
}
