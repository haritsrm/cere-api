<?php

namespace App\Http\Resources\Tryout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\Models\Kelas;
use App\Models\Question;
use App\Models\AttemptTryout;

class TryoutCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attempt = AttemptTryout::where('tryout_id',$this->id)
                ->where('user_id',$request->user()->id)
                ->select('left_attempt')
                ->first();        
        $lesson = Lesson::find($this->lesson_id)->name;
        $passing_percentage = Lesson::find($this->lesson_id)->passing_percentage;
        $class = Kelas::find($this->class_id)->name_class;
        $question = Question::where('tryout_id',$this->id)->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'lesson' => $lesson,
            'passing_percentage' => $passing_percentage,
            'class' => $class,
            'number_of_question' => $question,
            'instruction' => $this->instruction,
            'duration' => $this->duration,
            'attempt_count' => $this->attempt_count,
            'left_attempt' => $attempt,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'price' => $this->price,
            'scoring_system' => $this->scoring_system
        ];
    }
}
