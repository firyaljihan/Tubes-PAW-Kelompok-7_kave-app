<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Mahasiswa extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guard = 'mahasiswas';

    protected $fillable = [
        'nim', 'nama', 'prodi','jenis_kelamin','email', 'password'
    ];

    protected $hidden = [
        'password',
    ];
}
