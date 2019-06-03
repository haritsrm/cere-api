<?php

namespace App\Http\Resources\Video;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'video_url' => $this->video_url,
            'last_seen' => (!is_null($this->lastSeen()->where('user_id', $request->user()->id)->first()) ? $this->lastSeen()->where('user_id', $request->user()->id)->first()->updated_at->diffForHumans() : null),
        ];
    }
}
