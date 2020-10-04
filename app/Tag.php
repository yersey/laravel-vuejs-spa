<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];
    
    public function Entry()
    {
        return $this->belongsToMany('App\Entry');
    }
    
    public function Post()
    {
        return $this->belongsToMany('App\Post');
    }
    
    public function User()
    {
        return $this->belongsToMany('App\User');
    }
}
