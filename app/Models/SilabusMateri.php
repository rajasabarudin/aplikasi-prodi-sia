<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SilabusMateri extends Model
{
    use HasFactory;

    protected $table = 'silabus_materis';
    protected $fillable = [
        'silabus_id',
        'pertemuan',
        'materi',
    ];

    public function silabus()
    {
        return $this->belongsTo(Silabus::class, 'silabus_id');
    }
}
