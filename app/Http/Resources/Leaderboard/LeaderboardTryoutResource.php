<?php

namespace App\Http\Resources\Leaderboard;

use Illuminate\Http\Resources\Json\JsonResource;
class LeaderboardTryoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'attempt' => $this->counts
        ];
    }
}
