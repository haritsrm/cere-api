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
        $lesson = Lesson::find($this->lesson_id)->passing_percentage;
        $class = Kelas::find($this->class_id)->name_class;

        return [
            'id', $this->id,
            'name' => $this->name,
            'passing_percentage' => $lesson,
            'class' => $class,
            'instruction' => $this->instruction,
            'duration' => $this->duration,
            'attempt_count' => $this->attempt_count,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'price' => $this->price,
            'scoring_system' => $this->scoring_system
        ];
    }
}
