<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Message
 *
 * @property int $id
 * @property string $message
 * @property int $user_id
 * @property int $to_id
 * @property string $conversation
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $to
 * @property-read \App\User $user
 * @property-read \App\User $withh
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereConversation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    protected $fillable = [
        'to_id', 'message',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
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
        if($this->user == auth()->user()){
            return $this->belongsTo(User::class, 'to_id'); 
        } 
        return $this->belongsTo(User::class, 'user_id');
    }
}
