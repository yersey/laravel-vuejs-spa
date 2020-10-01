<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'to_id', 'message',
    ];
    
    public function User()
    {
        return $this->belongsTo('App\User');
    }
    
    public function to()
    {
        return $this->belongsTo('App\User', 'to_id');
    }
    
    public function isRead()
    {
        if($this->read_at != NULL)
            return true;
        else return false;
        
    } 
    
    public function markAsRead()
    {
        if(!$this->isRead() && $this->to_id == auth()->user()->id){
            $this->read_at = now();
            $this->save();
        }   
    }

    public function withh()
    {
        if($this->user == auth()->user())
            return $this->belongsTo('App\User', 'to_id');
        else return $this->belongsTo('App\User', 'user_id');
    }
}
