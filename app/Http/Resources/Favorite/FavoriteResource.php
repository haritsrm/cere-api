<?php

namespace App\Http\Resources\Favorite;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;

class FavoriteResource extends JsonResource
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
            'id' => $this->id,
            'course' => [
                'title' => $course->title,
                'cover' => $course->cover,
                'description' => $course->description,
                'teacher' => [
                    'name' => User::find($course->user_id)->name,
                ],
                'rating' => round($course->reviews()->avg('star')),
            ],
            'href' => [
                'link' => route('favorite/detail', [$course->id, $this->id]),
            ],
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
