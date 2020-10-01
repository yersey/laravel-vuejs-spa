<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plus extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = auth()->user()->id;
        });
    }

    public function plusable()
    {
        return $this->morphTo();
    }

    public function User()
    {
        return $this->belongsTo('App\User');
    }
}
