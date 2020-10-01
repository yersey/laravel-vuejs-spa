<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = auth()->user()->id;
        });
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function Comment()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Plus()
    {
        return $this->morphMany('App\Plus', 'plusable');
    }

    public function isPlus()
    {   
        return $this->morphMany('App\Plus', 'plusable')->where('user_id', auth()->user()->id)->exists();
    }
    
}
