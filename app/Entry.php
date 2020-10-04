<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = [
        'body'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function plus()
    {
        return $this->morphMany(Plus::class, 'plusable');
    }

    public function isPlus()
    {   
        return $this->morphMany(Plus::class, 'plusable')->where('user_id', auth()->user()->id)->exists();
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class);
    }  
}
