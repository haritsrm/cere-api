<?php

namespace App\Http\Resources\Cerecall;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;

class AvailTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $lesson = Lesson::where('id',$this->lesson_id)->first();
        return [
            'teacher_id' => $this->teacher_id,
            'status' => $this->status,
            'name' => $this->name,
            'lesson' => $this->lesson_id,
            // 'rating' => $lesson->lesson,
            // 'photo' => $lesson->lesson,
        ];
    }
}
