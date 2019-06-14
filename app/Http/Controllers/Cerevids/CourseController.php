<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\CourseService;
use App\Http\Resources\Lesson\LessonCollection;
use App\Http\Resources\Classroom\ClassCollection;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Course\CourseResource;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Kelas;
use App\Models\LastSeen;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->course = new CourseService;
    }

    public function index()
    {
        $courses = $this->course->browse();

        return CourseCollection::collection($courses);
    }

    public function indexByLesson($lesson_id)
    {
        $lessons = Lesson::find($lesson_id);

        return new LessonCollection($lessons);
    }

    public function lessonByClass($class_id)
    {
        $kelas = Kelas::find($class_id)->lessons;

        return ClassCollection::collection($kelas);
    }

    public function create(Request $req)
    {
        $req->validate([
            'cover' => 'image|required|mimes:jpeg,png,jpg,gif,svg'
          ]);
        $image = $req->file('cover');
        if(empty($image)){
            $namaFile = "null";
        }else{
            $extension = $image->getClientOriginalExtension();
            $namaFile = public_path().'/images/cerevid/'.$req->title.'.'.$extension;
            $req->file('cover')->move('images/cerevid/', $namaFile);
        }
        $result = $this->course->create([
            'title' => $req->title,
            'cover' => $namaFile,
            'description' => $req->description,
            'curriculum' => $req->curriculum,
            'lesson_id' => $req->lesson_id,
            'user_id' => $req->user()->id,
        ]);

        return (new CourseResource($result))
                ->additional([
                    'status' => true,
                    'message' => 'Succesfully add '.$result->title
                ]);
    }

    public function find($id, Request $req)
    {
        $course = $this->course->find($id);

        return new CourseResource($course);
    }

    public function update($id, Request $req)
    {
        $req->validate([
            'cover' => 'image|required|mimes:jpeg,png,jpg,gif,svg'
          ]);
        $image = $req->file('cover');
        if(empty($image)){
            $namaFile = "null";
        }else{
            $extension = $image->getClientOriginalExtension();
            $namaFile = 'http://api.ceredinas.id/images/cerevid/'.$req->title.'.'.$extension;
            $req->file('cover')->move('images/cerevid/', $namaFile);
        }
        $result = $this->course->update($id, [
            'title' => $req->title,
            'cover' => $namaFile,
            'description' => $req->description,
            'curriculum' => $req->curriculum,
            'lesson_id' => $req->lesson_id,
            'user_id' => $req->user_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Succesfully update '.$req->title
        ],201);
    }


    public function delete($id)
    {
        $result = $this->course->destroy($id);

        return $result;
    }

    public function indexByTeacher($id){
        $courses = Course::where('user_id','=',$id)->get();
        return CourseCollection::collection($courses);        
    }
}
