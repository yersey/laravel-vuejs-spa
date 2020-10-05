<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'body', 'imgurl'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dig()
    {
        return $this->hasMany(Dig::class);
    }

    public function isDig()
    {   
        return $this->hasMany(Dig::class)->where('user_id', auth()->user()->id)->exists();
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
