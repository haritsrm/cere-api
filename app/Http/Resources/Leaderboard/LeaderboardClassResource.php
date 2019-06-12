<?php

namespace App\Http\Resources\Leaderboard;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Models\Cereout;
class LeaderboardClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('id','=',$this->user_id)->first();
        // $score = Cereout::where('user_id','=',$this->user_id)
        //         ->where('class_id','=',$this->class_id)
        //         ->avg('score');
        return [
            'name' => $user->name,
            'score' => $this->scores
        ];
    }
}
