<?php

namespace App\Http\Resources\Cereout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tryout;
class MyCereoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tryout = Tryout::where('id',$this->tryout_id)->first();
        return [
            'id' => $this->id,
            'tryout_id' => $this->tryout_id,      
            'my_time' => $this->my_time, 
            'score' => number_format($this->score, 2),
            'total_answer' => $this->total_answer,
            'correct_answered' => $this->correct_answered,
            'incorrect_answered' => $this->incorrect_answered,
            'left_answered' => $this->left_answered,
            'result_status' => $this->result_status,
            'scoring_system' => $tryout->scoring_system,
            'created_at' => $this->created_at
        ];
    }
}
