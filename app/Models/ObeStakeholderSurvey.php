<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObeStakeholderSurvey extends Model
{
    use HasFactory;

    protected $table = 'obe_stakeholder_surveys';
    protected $fillable = [
        'tahun',
        'aspek_penilaian',
        'nilai_sangat_baik',
        'nilai_baik',
        'nilai_cukup',
        'nilai_kurang',
        'responden_count',
    ];
}
