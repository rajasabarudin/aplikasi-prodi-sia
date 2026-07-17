<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpl extends Model
{
    use HasFactory;

    protected $table = 'cpls';

    protected $fillable = ['kode_cpl', 'deskripsi_cpl'];

    public function cpmks()
    {
        return $this->hasMany(Cpmk::class, 'cpl_id');
    }
}
