<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wpis extends Model
{
    protected $fillable = [
        'body'
    ];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Comment()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function Plus()
    {
        return $this->morphMany('App\Plus', 'plusable');
    }

    public function isPlus()
    {   
        return $this->morphMany('App\Plus', 'plusable')->where('user_id', auth()->user()->id)->exists();
    }

    public function Tag()
    {
        return $this->belongsToMany('App\Tag');
    }  
}
