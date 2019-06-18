<?php

namespace App\Http\Resources\Leaderboard;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Cereout;
use Carbon\Carbon;
class ChartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $today =  Carbon::now()->format('F');
        $student = Cereout::where('tryout_id','=',$this->id)
            ->where('user_id','=',$request->user()->id)
            ->orderBy('created_at','ASC')
            ->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'score_student' => $this->score,
            'month' => $today
        ];
    }
}
