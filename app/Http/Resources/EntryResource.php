<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentResource;
use Carbon\Carbon;

class EntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'user_avatar' => $this->user->avatar,
            'created_at' => $this->created_at,
            'when' => (new Carbon($this->created_at))->locale('pl')->diffForHumans(),
            'pluses' => $this->plus()->count(),
            'isPlus' => auth()->user() ? $this->isPlus() : false,
            'top_comments' => CommentResource::collection($this->comment()->withCount('plus')->orderBy('plus_count', 'desc')->limit(2)->get()),
            'comments' => CommentResource::collection($this->comment),
        ];
    }
    
}
