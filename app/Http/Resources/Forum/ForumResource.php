<?php

namespace App\Http\Resources\Forum;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;
use App\User;

class ForumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $course = Course::findOrFail($this->course_id);

        return [
            'id' => $this->id,
            'body' => $this->body,
            'user' => User::find($this->user_id)->name,
            'comments' => ($this->forums ? ForumCollection::collection($this->forums) : null),
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
