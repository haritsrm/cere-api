<?php

namespace App\Http\Resources\Cerecall;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Lesson;
use App\Models\HistoryCall;
use DB;
class AvailTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lesson = Lesson::where('id',$this->lesson_id)->first();
        $photo = url('/images/student/'.$this->photo_url);
        $rating = HistoryCall::select('rating',DB::raw('avg(rating) as rating'))
            ->where('teacher_id',$this->teacher_id)
            ->first();
        return [
            'teacher_id' => $this->teacher_id,
            'status' => $this->status,
            'name' => $this->name,
            'lesson' => $lesson->name,
            'rating' => number_format((float)$rating->rating, 1, '.', ''),
            'photo' => $photo,
        ];
    }
}
