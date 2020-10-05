<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entry
 *
 * @property int $id
 * @property string $body
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comment
 * @property-read int|null $comment_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Plus[] $plus
 * @property-read int|null $plus_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tag
 * @property-read int|null $tag_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Entry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entry query()
 * @method static \Illuminate\Database\Eloquent\Builder|Entry whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entry whereUserId($value)
 * @mixin \Eloquent
 */
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
