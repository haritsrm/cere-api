<?php

namespace App\Http\Resources\Leaderboard;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Models\Cereout;
class LeaderboardLessonResource extends JsonResource
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
        $score = Cereout::where('user_id','=',$this->user_id)
                ->where('lesson_id','=',$this->lesson_id)
                ->avg('score');
        return [
            'name' => $user->name,
            'score' => $score
        ];
    }
}
