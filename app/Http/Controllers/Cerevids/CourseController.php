<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\CourseService;
use App\Http\Resources\Lesson\LessonCollection;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Course\CourseResource;
use App\Models\Lesson;

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

        return LessonCollection::collection($lessons);
    }

    public function create(Request $req)
    {
        $result = $this->course->create([
            'title' => $req->title,
            'cover' => $req->cover,
            'description' => $req->description,
            'curriculum' => $req->curriculum,
            'lesson_id' => $req->lesson_id,
            'teacher_id' => $req->teacher_id
        ]);

        return (new CourseResource($result))
                ->additional([
                    'status' => 'success',
                    'message' => 'Succesfully add '.$result->title
                ]);
    }

    public function find($id)
    {
        $course = $this->course->find($id);

        return new CourseResource($course);
    }

    public function update($id, Request $req)
    {
        $result = $this->course->update($id, [
            'title' => $req->title,
            'cover' => $req->cover,
            'description' => $req->description,
            'curriculum' => $req->curriculum,
            'lesson_id' => $req->lesson_id,
            'teacher_id' => $req->teacher_id
        ]);

        return new CourseResource($result);
    }

    public function delete($id)
    {
        $result = $this->course->destroy($id);

        return $result;
    }
}
