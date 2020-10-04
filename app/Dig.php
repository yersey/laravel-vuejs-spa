<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dig extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = auth()->user()->id;
        });
    }

    public function Post()
    {
        return $this->belongsTo('App\Post');
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }
}
