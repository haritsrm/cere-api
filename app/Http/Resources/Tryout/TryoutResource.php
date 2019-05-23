<?php

namespace App\Http\Resources\Tryout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\Models\Kelas;

class TryoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lesson = Lesson::find($this->lesson_id)->name;
        $class = Kelas::find($this->class_id)->name;

        return [
            'name' => $this->name,
            'lesson' => $lesson,
            'class' => $class,
            'instruction' => $this->instruction,
            'questions' => $this->questions,
            'duration' => $this->duration,
            'attempt_count' => $this->attempt_count,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'price' => $this->price,
            'scoring_system' => $this->scoring_system
        ];
    }
}
