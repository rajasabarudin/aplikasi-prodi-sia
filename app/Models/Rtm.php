<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rtm extends Model
{
    use HasFactory;

    protected $table = 'rtms';
    protected $fillable = [
        'rps_id',
        'nomor_dokumen',
        'dosen_pengampu',
        'semester',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class, 'rps_id');
    }

    public function tugas()
    {
        return $this->hasMany(RtmTugas::class, 'rtm_id');
    }
}
