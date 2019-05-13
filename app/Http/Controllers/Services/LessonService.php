<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lesson;

class LessonService extends Controller
{
    public function __construct()
    {
        $this->newLesson = new Lesson;
    }

    public function browse()
    {
        return $this->newLesson->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newLesson->create($req);
    }

    public function find($id)
    {
        return $this->newLesson->find($id);
    }

    public function update($id, Array $req)
    {
        $this->find($id)->update($req);
    }

    public function destroy($id)
    {
        $this->find($id)->delete();
    }
}
