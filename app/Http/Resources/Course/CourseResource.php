<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
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
            'description' => $this->description,
            'curriculum' => $this->curriculum,
            'lesson' => [
                'category' => $lesson->lesson_category,
                'name' => $lesson->name,
            ],
            'teacher' => $this->teacher_id,
            'href' => [
                'forums' => 'unlinked',
                'reviews' => route('reviews', $this->id),
            ],
            'rating' => round($this->reviews()->avg('star')),
            'created' => $this->created_at->diffForHumans()
        ];
    }
}
