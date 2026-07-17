<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Akun extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'akun';

    protected $fillable = [
        'username',
        'email',
        'password',
        'level',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function hasPermission($menu)
    {
        if ($this->level === 'king') {
            return true;
        }
        return \App\Models\HakAkses::where('level', $this->level)->where('menu', $menu)->exists();
    }
}
