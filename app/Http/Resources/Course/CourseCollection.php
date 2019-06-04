<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\User;
use App\Http\Resources\Section\SectionResource;

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

        $sum = 0;
        $sectionsResponse = SectionResource::collection($this->sections);
        foreach ($sectionsResponse as $key => $sec) {
            $sum += $sec['progress'];
        }
        if ($sum > 0 || count($this->sections) > 0) {
            $progress = $sum / count($this->sections);
        }
        else {
            $progress = 0;
        }

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
            'progress' => $progress,
            'rating' => round($this->reviews()->avg('star')),
        ];
    }
}
