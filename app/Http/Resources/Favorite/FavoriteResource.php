<?php

namespace App\Http\Resources\Favorite;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;
use App\User;

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
        return [
            'id' => $this->id,
            'course' => [
                'title' => $this->course->title,
                'cover' => $this->course->cover,
                'description' => $this->course->description,
                'teacher' => [
                    'name' => User::find($this->course->user_id)->name,
                ],
                'rating' => round($this->course->reviews()->avg('star')),
            ],
            'user' => User::find($this->user_id)->name,
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
