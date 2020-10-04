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

    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plus()
    {
        return $this->morphMany(Plus::class, 'plusable');
    }

    public function isPlus()
    {   
        return $this->morphMany(Plus::class, 'plusable')->where('user_id', auth()->user()->id)->exists();
    }
}
