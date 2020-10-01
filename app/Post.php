<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'body', 'imgurl'
    ];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Wykop()
    {
        return $this->hasMany('App\Wykop');
    }

    public function isWykop()
    {   
        return $this->hasMany('App\Wykop')->where('user_id', auth()->user()->id)->exists();
    }

    public function Comment()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function Tag()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
