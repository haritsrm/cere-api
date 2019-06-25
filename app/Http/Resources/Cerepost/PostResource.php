<?php

namespace App\Http\Resources\Cerepost;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Models\Cerepost;
use App\Models\Like;
use App\Models\Comment;

class PostResource extends JsonResource
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
        $cerepost = Cerepost::where('id',$this->cerepost_id)->first();
        $likes = Like::where('post_id',$this->id)->get();
        $liked = Like::where('post_id',$this->id)->where('user_id',$request->user()->id)->get();
        if(count($liked)>0){
            $liked = true;
        }else{
            $liked = false;
        }
        $comments = Comment::where('post_id',$this->id)->where('comment_id',null)->get();
        return [
            'id' => $this->id,
            'user' => $user->name,
            'cerepost' => $cerepost->cerepost_name,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
            'likes' => count($likes),
            'liked' => $liked,
            'comments' => count($comments),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans()
        ];
    }
}
