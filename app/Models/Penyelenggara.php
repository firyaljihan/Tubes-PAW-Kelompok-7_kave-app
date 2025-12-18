<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Penyelenggara extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guard = 'penyelenggaras';

    protected $fillable = [
        'nama_penyelenggara',
        'email',
        'password',
        'phone'
    ];

    protected $hidden = [
        'password',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'penyelenggara_id');
    }
}
