<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Sale extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $guard = 'sale';
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'admin_id',
    ];
    public function getGuard()
    {
        return 'sale';
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
    
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
    
}
