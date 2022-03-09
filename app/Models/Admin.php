<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
    ];

    public function getGuard()
    {
        return 'admin';
    }
    
    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
