<?php

namespace App\Http\Controllers\Services\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Course;

class SectionService extends Controller
{
    public function __construct()
    {
        $this->newSection = new Section;
        $this->newCourse = new Course;
    }

    public function browse($course_id)
    {
        return $this->newCourse->find($course_id)->sections()->get();
    }

    public function create(Array $req)
    {
        return $this->newSection->create($req);
    }

    public function find($id)
    {
        return $this->newSection->find($id);
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
