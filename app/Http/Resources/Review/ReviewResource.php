<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;
use App\Models\Review;
use App\User;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $rated = Review::where('user_id','=',$request->user()->id)->count();
        return [
                'id' => $this->id,
                'star' => $this->star,
                'body' => $this->body,
                'user' => User::find($this->user_id)->name,
                'rated' => $rated,
                'posted' => $this->created_at->diffForHumans(),
                'rated' => $rated
            
        ];
    }
}
