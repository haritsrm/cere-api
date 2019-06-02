<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Video\VideoResource;
use App\Http\Resources\Text\TextResource;
use App\Http\Resources\Quiz\QuizResource;
use App\Models\Course;
use App\User;
use App\Models\QuestionQuiz;

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
        $videos = $this->videos();
        $texts  = $this->texts();
        $quiz   = $this->quiz();

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
            'videos' => VideoResource::collection($videos->get()),
            'texts' => TextResource::collection($texts->get()),
            'quiz' => QuizResource::collection($quiz->get()),
            'href' => [
                'link' => route('section/detail', [$course->id, $this->id]),
            ],
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
