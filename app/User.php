<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar'
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

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function Post()
    {
        return $this->hasMany('App\Post');
    }

    public function Wykop()
    {
        return $this->hasMany('App\Wykop');
    }
    
    public function Wpis()
    {
        return $this->hasMany('App\Wpis');
    }
    
    public function Comment()
    {
        return $this->hasMany('App\Comment');
    }
    
    public function Plus()
    {
        return $this->hasMany('App\Plus');
    }

    public function Message()
    {
        return $this->hasMany('App\Message');
    } 

    public function MessagesSendToMe()
    {
        return $this->hasMany('App\Message', 'to_id');
    }

    public function Tag()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
