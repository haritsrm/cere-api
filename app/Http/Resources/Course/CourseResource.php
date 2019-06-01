<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\User;
use App\Models\Favorite;
use App\Models\Learned;
use App\Http\Resources\Section\SectionCollection;
use App\Http\Resources\Forum\ForumCollection;
use App\Http\Resources\Review\ReviewResource;

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
        $favorites = Favorite::where('user_id', $request->user()->id)->where('course_id', $this->id)->get();
        if(count($favorites) == 0){
            $favorite_result = 0;
        }
        else{
            $favorite_result = $favorites->first()->id;
        }

        $learned = Learned::where('user_id', $request->user()->id)->where('course_id', $this->id)->get();
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
                'class_id' => $this->lesson->class_id,
                'name' => $this->lesson->name,
                'passing_percentage' => $this->lesson->passing_percentage
            ],
            'favorite' => [
                'id' => $favorite_result,
            ],
            'learned' => $learned_result,
            'teacher' => [
                'name' => User::find($this->user_id)->name,
            ],
            'sections' => SectionCollection::collection($this->sections),
            'forums' => ForumCollection::collection($this->forums),
            'reviews' => ReviewResource::collection($this->reviews),
            'rating' => round($this->reviews()->avg('star')),
            'created' => $this->created_at->diffForHumans(),
            'last_seen' => ($this->lastSeen()->where('user_id', $request->user()->id)->first()->updated_at->diffForHumans() ? $this->lastSeen()->where('user_id', $request->user()->id)->first()->updated_at->diffForHumans() : null),
        ];
    }
}
