<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Silabus extends Model
{
    use HasFactory;

    protected $table = 'silabus';
    protected $fillable = [
        'rps_id',
        'kode_dokumen',
        'cpmk',
        'sub_cpmk',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class, 'rps_id');
    }

    public function materis()
    {
        return $this->hasMany(SilabusMateri::class, 'silabus_id');
    }
}
