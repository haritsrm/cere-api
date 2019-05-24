<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\User;
use App\Models\Favorite;

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
        $learned = false;
        $lesson = Lesson::find($this->lesson_id);
        try {
            $favorite_result = Favorite::where('user_id', $request->user()->id)
                                ->where('course_id', $this->id)->first()->id;
        }
        catch(ErrorException $e) {
            $favorite_result = 0;
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
            'favorite' => [
                'id' => $favorite_result,
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
