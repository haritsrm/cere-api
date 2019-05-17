<?php

namespace App\Http\Resources\Cerevid;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;

class CerevidResource extends JsonResource
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
            'student' => 'student name',
            'href' => [
                'link' => route('cerevid/detail', [$course->id, $this->id]),
            ],
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
