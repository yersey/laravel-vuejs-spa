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

    public function created_at_(){
        $secs = strtotime($this->created_at);
        $ago = time()-$secs;
        $agoo = $ago;
        
        $date = [
            'year' => 0,
            'month' => 0,
            'week' => 0,
            'day' => 0,
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
        ];
        
        while($ago > 31556925){
            $date['year'] += 1;
            $ago-=31556926;
        }
        while($ago > 2629742){
            $date['month'] += 1;
            $ago-=2629743;
        }
        while($ago > 604799){
            $date['week'] += 1;
            $ago=-604800;
        }
        while($ago > 86399){
            $date['day'] += 1;
            $ago-=86400;
        }
        while($ago > 3599){
            $date['hour'] += 1;
            $ago-=3600;
        }
        while($ago > 59){
            $date['minute'] += 1;
            $ago-=60;
        }
        $date['second'] = $ago;
        
        //$when = 'opublikowano';
        $when = '';
        
        if($date['year'])
            $when .= ' '.$date['year'].' lat';
        
        if($date['month'])
            $when .= ' '.$date['month'].' mies.';
        
        if($date['month'] < 1 && $agoo < 2629743){
            if($date['week'])
                $when .= ' '.$date['week'].' tyg.';
        }
        
        if($date['day'] < 7 && $agoo < 604800){
            if($date['day'])
                $when .= ' '.$date['day'].' dni';
        }
        
        if($date['hour'] < 24 && $agoo < 86400){
            if($date['hour'])
                $when .= ' '.$date['hour'].' godz.';
        }
        
        if($date['minute'] < 60 && $agoo < 3600){
            if($date['minute'])
                $when .= ' '.$date['minute'].' min.';
        }
        
        if($date['second'] < 60 && $agoo < 60){
            if($date['second'] >= 0)
                $when .= ' '.$date['second'].' sek.';
        }
        
        $when .= ' temu';
        
        return $when;
    }
}
