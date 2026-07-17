<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'kelas',
    ];

    public function hki()
    {
        return $this->hasMany(Hki::class, 'nim', 'nim');
    }

    public function prestasi()
    {
        return $this->hasMany(PrestasiMahasiswa::class, 'nim', 'nim');
    }

    public function organisasi()
    {
        return $this->hasMany(OrganisasiMahasiswa::class, 'nim', 'nim');
    }

    public function tugas()
    {
        return $this->hasMany(TugasMahasiswa::class, 'nim', 'nim');
    }

    public function capstone()
    {
        return $this->hasMany(CapstoneMahasiswa::class, 'nim', 'nim');
    }
}
