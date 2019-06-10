<?php

namespace App\Http\Resources\Cereout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Cereout;
use DB;

class SummaryCereoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ranking = Cereout::select('user_id',DB::raw('max(score) as scores'))
                ->orderBy('scores', 'DESC')
                ->groupBy('user_id')
                ->where('tryout_id','=',$this->tryout_id)
                ->get();
        $userTryout = Cereout::select('user_id',DB::raw('max(score) as scores'))
                ->orderBy('scores', 'DESC')
                ->groupBy('user_id')
                ->where('tryout_id','=',$this->tryout_id)
                ->count();        
        $rank=0;
        foreach ($ranking as $ranking ) {
            $rank++;
            if($ranking->user_id==$this->user_id){
                break;
            }
        }

        return [
            'time' => $this->my_time,
            'score' => $this->score,
            'correct_answered' => $this->correct_answered,
            'incorrect_answered' => $this->incorrect_answered,
            'left_answered' => $this->left_answered,
            'result_status' => $this->result_status,
            'tryout_ranking' => $rank,
            'tryout_user' => $userTryout
        ];
    }
}
