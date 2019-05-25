<?php

namespace App\Http\Resources\Learned;

use Illuminate\Http\Resources\Json\JsonResource;

class LearnedResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'cover' => $this->cover,
            'teacher' => $this->name,
            'course_id' => $this->course_id,
        ];
    }
}
