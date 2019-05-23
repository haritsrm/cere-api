<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Lesson\LessonResource;

class ClassResource extends JsonResource
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
            'name' => $this->name_class,
            'lessons' => LessonResource::collection($this->lessons)
        ];
    }
}
