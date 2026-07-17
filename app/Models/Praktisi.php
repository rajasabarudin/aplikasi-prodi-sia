<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Praktisi extends Model
{
    use HasFactory;

    protected $table = 'praktisis';

    protected $fillable = [
        'nama_praktisi',
        'pendidikan_terakhir',
        'ts_id',
        'kode_matakuliah',
        'kelas',
        'link_ijazah',
        'link_sertifikasi',
        'link_dokumen',
    ];

    protected $casts = [
        'kode_matakuliah' => 'array',
        'kelas' => 'array',
    ];

    /**
     * Get the TS associated with the practitioner record.
     */
    public function ts()
    {
        return $this->belongsTo(Ts::class, 'ts_id');
    }

    /**
     * Get all Matakuliah objects for this practitioner.
     */
    public function getMatakuliahsAttribute()
    {
        $codes = $this->kode_matakuliah ?? [];
        return Matakuliah::whereIn('kode_matakuliah', $codes)->get();
    }
}
