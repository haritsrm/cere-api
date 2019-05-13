<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Services\CourseService;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Course\CourseResource;

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

        return new CourseResource($result);
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

        return new CourseResource($result);
    }
}
