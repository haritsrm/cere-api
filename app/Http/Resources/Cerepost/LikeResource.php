<?php

namespace App\Http\Resources\Cerepost;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('id',$this->user_id)->first();
        return [
            'name' => $user->name
        ];
    }
}
