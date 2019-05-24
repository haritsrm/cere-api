<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\User;

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
            'id' => $this->id,
            'title' => $this->title,
            'cover' => $this->cover,
            'description' => $this->description,
            'curriculum' => $this->curriculum,
            'lesson' => [
                'class_id' => $lesson->class_id,
                'name' => $lesson->name,
                'passing_percentage' => $lesson->passing_percentage
            ],
            'teacher' => [
                'name' => User::find($this->user_id)->name,
            ],
            'sections' => $this->sections,
            'forums' => $this->forums,
            'reviews' => $this->reviews,
            'rating' => round($this->reviews()->avg('star')),
            'created' => $this->created_at->diffForHumans()
        ];
    }
}
