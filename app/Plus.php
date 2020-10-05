<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Plus
 *
 * @property int $id
 * @property int $user_id
 * @property int $plusable_id
 * @property string $plusable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $plusable
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Plus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plus wherePlusableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plus wherePlusableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plus whereUserId($value)
 * @mixin \Eloquent
 */
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
