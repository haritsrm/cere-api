<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\User;
use App\Models\Favorite;
use App\Models\LastSeen;
use App\Models\Learned;
use App\Http\Resources\Section\SectionCollection;
use App\Http\Resources\Section\SectionResource;
use App\Http\Resources\Forum\ForumCollection;
use App\Http\Resources\Review\ReviewResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $favorites = Favorite::where('user_id', $request->user()->id)->where('course_id', $this->id)->get();
        if(count($favorites) == 0){
            $favorite_result = 0;
        }
        else{
            $favorite_result = $favorites->first()->id;
        }

        $learned = Learned::where('user_id', $request->user()->id)->where('course_id', $this->id)->get();
        if(count($learned) == 0){
            $learned_result = false;
        }
        else{
            $learned_result = true;
        }
        
        $index = 0;
        $progress = [];
        foreach ($this->sections as $sec) {
            $videos = $sec->videos()->get();
            $texts  = $sec->texts()->get();
            $quiz   = $sec->quiz()->get();
    
            $last_seen = LastSeen::where('user_id', $request->user()->id)->get();
            $i = 0;
    
            foreach ($videos as $key => $video) {
                foreach ($last_seen as $key => $ls) {
                    if ($video->id == $ls->video_id) {
                        $i++;
                    }
                }
            }
    
            foreach ($texts as $key => $text) {
                foreach ($last_seen as $key => $ls) {
                    if ($text->id == $ls->text_id) {
                        $i++;
                    }
                }
            }
    
            foreach ($quiz as $key => $q) {
                foreach ($last_seen as $key => $ls) {
                    if ($q->id == $ls->quiz_id) {
                        $i++;
                    }
                }
            }
    
            $materialCounts = count($videos) + count($texts) + count($quiz);
            if ($materialCounts > 0) {
                $progress[$index] = ($i/$materialCounts)*100;
            }
            else {
                $progress[$index] = 0;
            }
            $index++;
        }
        
        if (array_sum($progress) > 0 || count($progress) > 0) {
            $progress_total = array_sum($progress)/count($progress);
        }
        else {
            $progress_total = 0;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'cover' => $this->cover,
            'description' => $this->description,
            'curriculum' => $this->curriculum,
            'lesson' => [
                'id' => $lesson->id,
                'class_id' => $this->lesson->class_id,
                'name' => $this->lesson->name,
                'passing_percentage' => $this->lesson->passing_percentage
            ],
            'favorite' => [
                'id' => $favorite_result,
            ],
            'learned' => $learned_result,
            'teacher' => [
                'name' => User::find($this->user_id)->name,
            ],
            'sections' => SectionCollection::collection($this->sections),
            'forums' => ForumCollection::collection($this->forums),
            'reviews' => ReviewResource::collection($this->reviews),
            'rating' => round($this->reviews()->avg('star')),
            'progress' => round($progress_total),
            'created' => $this->created_at->diffForHumans(),
        ];
    }
}
