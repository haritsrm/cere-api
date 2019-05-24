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
            'course' => [
                'title' => $course->title,
                'cover' => $course->cover,
                'description' => $course->description,
                'teacher' => [
                    'name' => User::find($course->user_id)->name,
                ],
                'rating' => round($course->reviews()->avg('star')),
            ],
            'body' => $this->body,
            'user' => User::find($this->user_id)->name,
            'href' => [
                'link' => route('forum/detail', [$course->id, $this->id]),
            ],
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
