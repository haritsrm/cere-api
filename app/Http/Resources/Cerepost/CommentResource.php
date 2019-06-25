<?php

namespace App\Http\Resources\Cerepost;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
class CommentResource extends JsonResource
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
            'id' => $this->id,
            'post_id' => $this->post_id,
            'name' => $user->name,
            'content' => $this->content,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
