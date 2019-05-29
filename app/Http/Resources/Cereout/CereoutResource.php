<?php

namespace App\Http\Resources\Cereout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tryout;
class CereoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tryout = Tryout::where('id','=',$this->tryout_id)->get();
        return [
            'id' => $this->id,
            'tryout' => $tryout,
            'my_time' => $this->my_time, 
            'score' => $this->score,
            'total_answer' => $this->total_answer,
            'correct_answered' => $this->correct_answered,
            'incorrect_answered' => $this->incorrect_answered,
            'left_attempt' => $this->left_attempt,
            'result_status' => $this->result_status
        ];
    }
}
