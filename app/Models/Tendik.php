<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tendik extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nip_nik',
        'nama_lengkap',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'jabatan_tugas',
        'status_pegawai',
        'unit_kerja',
        'bukti_sertifikasi'
    ];
}
