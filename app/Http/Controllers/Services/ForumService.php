<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Course;

class ForumService extends Controller
{
    public function __construct()
    {
        $this->newForum = new Forum;
        $this->newCourse = new Course;
    }

    public function browse($course_id)
    {
        return $this->newCourse->find($course_id)->forums()->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newForum->create($req);
    }

    public function find($id)
    {
        return $this->newForum->find($id);
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
