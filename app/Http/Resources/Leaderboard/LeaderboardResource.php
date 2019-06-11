<?php

namespace App\Http\Resources\Leaderboard;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Models\Cereout;
class LeaderboardResource extends JsonResource
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
        return [
            'name' => $user->name,
            'score' => $this->scores
        ];
    }
}
