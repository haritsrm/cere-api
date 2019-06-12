<?php

namespace App\Http\Resources\Leaderboard;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Cereout;
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
        $student = Cereout::where('tryout_id','=',$this->id)
            ->groupBy('user_id')
            ->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_student' => count($student)
        ];
    }
}
