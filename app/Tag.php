<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }
    
    public function entry()
    {
        return $this->belongsToMany(Entry::class);
    }
    
    public function post()
    {
        return $this->belongsToMany(Post::class);
    }
    
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
