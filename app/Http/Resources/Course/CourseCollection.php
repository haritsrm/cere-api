<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;

class CourseCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lesson = Lesson::find($this->lesson_id);

        return [
            'title' => $this->title,
            'cover' => $this->cover,
            'lesson' => [
                'class_id' => $lesson->class_id,
                'name' => $lesson->name,
                'passing_percentage' => $lesson->passing_percentage
            ],
            'href' => [
                'link' => route('course/detail', $this->id),
            ],
            'rating' => round($this->reviews()->avg('star')),
        ];
    }
}
