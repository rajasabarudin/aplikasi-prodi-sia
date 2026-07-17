<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtmTugas extends Model
{
    use HasFactory;

    protected $table = 'rtm_tugas';
    protected $fillable = [
        'rtm_id',
        'minggu_ke',
        'tugas_ke',
        'bentuk_tugas',
        'judul_tugas',
        'sub_cpmk',
        'obyek_garapan',
        'metode_pengerjaan',
        'bentuk_format_luaran',
        'waktu_pengerjaan',
        'waktu_pengumpulan',
        'lain_lain',
        'daftar_rujukan',
    ];

    public function rtm()
    {
        return $this->belongsTo(Rtm::class, 'rtm_id');
    }

    public function penilaians()
    {
        return $this->hasMany(RtmPenilaian::class, 'rtm_tugas_id');
    }
}
