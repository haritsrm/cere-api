<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\Models\LastSeen;
use App\User;
use App\Http\Resources\Section\SectionResource;

class CourseCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lesson = Lesson::find($this->lesson_id);

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
                'class_id' => $lesson->class_id,
                'name' => $lesson->name,
                'passing_percentage' => $lesson->passing_percentage
            ],
            'teacher' => [
                'name' => User::find($this->user_id)->name,
            ],
            'href' => [
                'link' => route('course/detail', $this->id),
            ],
            'progress' => round($progress_total),
            'rating' => round($this->reviews()->avg('star')),
        ];
    }
}
