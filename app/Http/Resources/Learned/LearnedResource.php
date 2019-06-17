<?php

namespace App\Http\Resources\Learned;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;
use App\Models\LastSeen;

class LearnedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $course = Course::find($this->course_id);
        foreach ($course->sections as $sec) {
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
            'description' => $this->description,
            'cover' => $this->cover,
            'teacher' => $this->name,
            'course_id' => $this->course_id,
            'progress' => round($progress_total),
        ];
    }
}
