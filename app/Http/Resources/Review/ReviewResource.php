<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;

class ReviewResource extends JsonResource
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
            ],
            'star' => $this->star,
            'body' => $this->body,
            'student' => 'student name',
            'href' => [
                'link' => route('review/detail', [$course->id, $this->id]),
            ],
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
