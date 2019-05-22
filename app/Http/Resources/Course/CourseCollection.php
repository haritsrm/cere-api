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
        $lesson_category = Lesson::findOrFail($this->lesson_id)->lesson_category;

        return [
            'title' => $this->title,
            'cover' => $this->cover,
            'lesson_category' => $lesson_category,
            'href' => [
                'link' => route('course/detail', $this->id),
            ],
            'rating' => round($this->reviews()->avg('star')),
        ];
    }
}
