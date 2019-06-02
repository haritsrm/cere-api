<?php

namespace App\Http\Resources\Cereout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Answer;
use App\Models\Cereout;

class DetailCereoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $score = Cereout::where('id','=',$this->question_id)
        //     ->where('check_answer','=',true)
        //     ->count();
        $sum_right = Answer::where('question_id','=',$this->question_id)
            ->where('check_answer','=',true)
            ->count();
        $sum_wrong = Answer::where('question_id','=',$this->question_id)
            ->where('check_answer','=',false)
            ->count();    
        return [
            'id' => $this->id,
            'answer' => $this->answer,
            'mark' => $this->mark,
            'check_answer' => $this->check_answer,
            'user_right' => $sum_right,
            'user_wrong' => $sum_wrong,
            'score' => $this->score,
            'discussion' => [
                'question_id' => $this->question_id,
                'question' => $this->question,
                'explanation' => $this->explanation,
                'url_explanation' => $this->url_explanation
            ],
        ];
    }
}