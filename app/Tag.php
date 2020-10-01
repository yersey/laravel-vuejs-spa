<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];
    
    public function Wpis()
    {
        return $this->belongsToMany('App\Wpis');
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
