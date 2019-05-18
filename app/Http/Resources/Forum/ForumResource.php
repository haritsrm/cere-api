<?php

namespace App\Http\Resources\Forum;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;

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
            'course' => [
                'title' => $course->title,
                'cover' => $course->cover,
                'description' => $course->description,
            ],
            'body' => $this->body,
            'student' => $this->student_id,
            'teacher' => $this->teacher_id,
            'href' => [
                'link' => route('forum/detail', [$course->id, $this->id]),
            ],
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
