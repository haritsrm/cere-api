<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Services\LessonService;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->lesson = new LessonService;
    }

    public function index()
    {
        $lessons = $this->lesson->browse();

        return $lessons;
    }

    public function create(Request $req)
    {
        $result = $this->lesson->create([
            'lesson_category' => $req->lesson_category,
            'name' => $req->name,
        ]);

        return $result;
    }

    public function find($id)
    {
        $lesson = $this->lesson->find($id);

        return $lesson;
    }

    public function update($id, Request $req)
    {
        $result = $this->lesson->update($id, [
            'lesson_category' => $req->lesson_category,
            'name' => $req->name,
        ]);

        return $result;
    }

    public function delete($id)
    {
        $result = $this->lesson->destroy($id);

        return $result;
    }
}
