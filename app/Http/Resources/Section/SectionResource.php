<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Course;

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
            'course' => [
                'title' => $course->title,
                'cover' => $course->cover,
                'description' => $course->description,
            ],
            'title' => $this->title,
            'videos' => $videos,
            'texts' => $texts,
            'quiz' => $quiz,
            'href' => [
                'link' => route('section/detail', [$course->id, $this->id]),
            ],
            'posted' => $this->created_at->diffForHumans(),
        ];
    }
}
