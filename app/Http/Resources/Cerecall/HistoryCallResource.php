<?php

namespace App\Http\Resources\Cerecall;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Models\Kelas;
use App\Models\Lesson;
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
        $kelas=Kelas::where('id',$student->class_id)->first();
        $lesson=Lesson::where('id',$this->lesson_id)->first();
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
                'student_class' => $kelas->name_class,
                'student_photo' => $student_photo,
            ],
            'lesson' => $lesson->name,
            'rating' => $this->rating,
            'review' => $this->review,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
