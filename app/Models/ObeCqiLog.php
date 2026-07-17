<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObeCqiLog extends Model
{
    use HasFactory;

    protected $table = 'obe_cqi_logs';
    protected $fillable = [
        'rps_id',
        'semester',
        'cpl_id',
        'analisis_masalah',
        'rencana_perbaikan',
        'status',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class, 'rps_id');
    }

    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'cpl_id');
    }
}
