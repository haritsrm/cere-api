<?php

namespace App\Http\Resources\Cerecall;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
class HistoryCallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $teacher=User::where('id',$this->teacher_id)->first();
        if($teacher->photo_url == null){
            $teacher_photo = null;
        }else{
            $teacher_photo = url('/images/student/'.$teacher->photo_url);               
        }
        $student=User::where('id',$this->student_id)->first();
        if($student->photo_url == null){
            $student_photo = null;
        }else{
            $student_photo = url('/images/student/'.$student->photo_url);               
        }
        return [
            'id' => $this->id,
            'teacher' => [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->name,
                'teacher_photo' => $teacher_photo,
            ],
            'student' => [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'student_photo' => $student_photo,
            ],
            'rating' => $this->rating,
            'review' => $this->review,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
