<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tryout;
use App\Models\Answer;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $weight = Answer::where('question_id',$this->id)->first();
        return [
            'id' => $this->id,
            'tryout_name' => $this->name,
            'duration' => $this->duration,
            'question' => $this->question,
            'option' => [
                ['option' => $this->option_a],
                ['option' => $this->option_b],
                ['option' => $this->option_c],
                ['option' => $this->option_d],
                ['option' => $this->option_e],
                ['option' => $this->option_f]
            ],
            'weight' => $weight->score,
            'correct_answer' => $this->correct_answer,
            'explanation' => $this->explanation,
            'url_explanation' => $this->url_explanation,
            'correct_score' => $this->correct_score,
            'incorrect_score' => $this->incorrect_score
        ];
    }
}
