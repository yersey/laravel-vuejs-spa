<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Dig
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Post $post
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Dig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dig query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dig wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dig whereUserId($value)
 * @mixin \Eloquent
 */
class Dig extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = auth()->user()->id;
        });
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
