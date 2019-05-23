<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Http\Resources\Classroom\ClassResource;
use App\Http\Resources\Lesson\LessonResource;

class EnvironmentController extends Controller
{
    public function classes()
    {
        $classes = Classroom::all();

        return ClassResource::collection($classes);
    }

    public function findClass($class_id)
    {
        $class = Classroom::find($class_id);

        return (new ClassResource($class))->additional([
            'status' => true
        ]);
    }

    public function lessons()
    {
        $lessons = Lesson::all();

        return LessonResource::collection($lessons);
    }

    public function findLesson($lesson_id)
    {
        $lesson = Lesson::find($lesson_id);

        return (new LessonResource($lesson))->additional([
            'status' => true
        ]);
    }
}
