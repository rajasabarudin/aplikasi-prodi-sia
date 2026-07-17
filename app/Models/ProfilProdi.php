<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilProdi extends Model
{
    use HasFactory;
    
    protected $table = 'profil_prodis';

    protected $fillable = [
        'deskripsi_profil',
        'visi_keilmuan',
        'nama_kaprodi',
        'foto_kaprodi',
        'informasi_staf',
        'akreditasi',
        'lama_masa_studi',
    ];
}
