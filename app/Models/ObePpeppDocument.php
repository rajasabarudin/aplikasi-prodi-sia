<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObePpeppDocument extends Model
{
    use HasFactory;

    protected $table = 'obe_ppepp_documents';

    protected $fillable = [
        'kriteria',
        'ppepp',
        'nama_dokumen',
        'file_path'
    ];
}
