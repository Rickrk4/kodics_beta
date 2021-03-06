<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    public function comics(){
        return $this->morphedByMany('App\Comic','readable');
    }

    public function directories(){
        return $this->morphedByMany('App\Directory','readable');
    }

    public function series(){
        return $this->morphedByMany('App\Series','readable');
    }

    public function collections(){
        return $this->morphedByMany('App\Collection','readable');
    }

    public function isAdmin()
    {
        return $this->roles()->where('role','admin')->exists();
    }
}
