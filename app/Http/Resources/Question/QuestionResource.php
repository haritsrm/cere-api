<?php

namespace App\Http\Resources\Question;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tryout;

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
        return [
            
            'id' => $this->id,
            'tryout_name' => $this->name,
            'duration' => $this->duration,
            'question' => $this->question,
            'option' => [
                ['option_a' => $this->option_a],
                ['option_b' => $this->option_b],
                ['option_c' => $this->option_c],
                ['option_d' => $this->option_d],
                ['option_e' => $this->option_e],
                ['option_f' => $this->option_f]
            ],
            'correct_answer' => $this->correct_answer,
            'explanation' => $this->explanation,
            'url_explanation' => $this->url_explanation,
            'correct_score' => $this->correct_score,
            'incorrect_score' => $this->incorrect_score
        ];
    }
}
