<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\User;

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
            'href' => [
                'link' => route('course/detail', $this->id),
            ],
            'rating' => round($this->reviews()->avg('star')),
            'last_seen' => (!is_null($this->lastSeen()->where('user_id', $request->user()->id)->first()) ? $this->lastSeen()->where('user_id', $request->user()->id)->first()->updated_at->diffForHumans() : null),
        ];
    }
}
