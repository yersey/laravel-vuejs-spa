<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentResource;
use Carbon\Carbon;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'imgurl' => $this->imgurl,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'user_avatar' => $this->user->avatar,
            'created_at' => $this->created_at,
            'when' => (new Carbon($this->created_at))->locale('pl')->diffForHumans(),
            'digs' => $this->dig()->count(),
            'tag' => $this->tag,
            'isDig' => auth()->user() ? $this->isDig() : false,
            'comments' => CommentResource::collection($this->comment),
        ];
    }
}
