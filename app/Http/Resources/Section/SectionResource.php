<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Video\VideoResource;
use App\Http\Resources\Text\TextResource;
use App\Http\Resources\Quiz\QuizResource;
use App\Models\Course;
use App\Models\LastSeen;
use App\Models\QuestionQuiz;
use App\User;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $course = Course::findOrFail($this->course_id);
        $videos = $this->videos()->get();
        $texts  = $this->texts()->get();
        $quiz   = $this->quiz()->get();

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
        $progress = ($i/$materialCounts)*100;

        return [
            'id' => $this->id,
            'course' => [
                'title' => $course->title,
                'cover' => $course->cover,
                'description' => $course->description,
                'teacher' => [
                    'name' => User::find($course->user_id)->name,
                ],
                'rating' => round($course->reviews()->avg('star')),
            ],
            'title' => $this->title,
            'videos' => VideoResource::collection($videos),
            'texts' => TextResource::collection($texts),
            'quiz' => QuizResource::collection($quiz),
            'href' => [
                'link' => route('section/detail', [$course->id, $this->id]),
            ],
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
