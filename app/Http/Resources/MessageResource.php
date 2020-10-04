<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MessageResource extends JsonResource
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
            'message' => $this->message,
            'user_id' => $this->user_id,
            'with_user_id' => $this->withh->id,
            'with_user_name' => $this->withh->name,
            'user_avatar' => $this->withh->avatar,
            'conversation' => $this->conversation,
            'created_at' => $this->created_at,
            'when' => (new Carbon($this->created_at))->locale('pl')->diffForHumans(),
            'read_at' => $this->read_at,
        ];
    }
}
