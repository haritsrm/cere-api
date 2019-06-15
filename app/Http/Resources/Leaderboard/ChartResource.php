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
            ->groupBy('user_id')
            ->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_student' => count($student),
            'month' => $today
        ];
    }
}
