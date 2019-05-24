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
        $lesson = Lesson::find($this->lesson_id);
        $favorites = Favorite::where('user_id', $request->user()->id)
                                    ->where('course_id', $this->id)->get();
        if(count($favorites) == 0){
            $favorite_result = 0;
        }
        else{
            $favorite_result = $favorites->first()->id;
        }

        $learned = Cerevid::where('user_id', $request->user()->id)
                                    ->where('course_id', $this->id)->get();
        if(count($learned) == 0){
            $learned_result = false;
        }
        else{
            $learned_result = true;
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
            'learned' => $learned_result,
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
