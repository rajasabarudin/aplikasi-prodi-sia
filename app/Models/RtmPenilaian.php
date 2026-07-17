<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtmPenilaian extends Model
{
    use HasFactory;

    protected $table = 'rtm_penilaians';
    protected $fillable = [
        'rtm_tugas_id',
        'indikator',
        'teknik_penilaian',
        'bobot_penilaian',
    ];

    public function tugas()
    {
        return $this->belongsTo(RtmTugas::class, 'rtm_tugas_id');
    }
}
