<?php

namespace App\Http\Controllers\Services\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cerevid;
use App\Models\Course;

class CerevidService extends Controller
{
    public function __construct()
    {
        $this->newCerevid = new Cerevid;
        $this->newCourse = new Course;
    }

    public function browse($course_id)
    {
        return $this->newCourse->find($course_id)->cerevids()->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newCerevid->create($req);
    }

    public function find($id)
    {
        return $this->newCerevid->find($id);
    }

    public function destroy($id)
    {
        $this->find($id)->delete();
    }
}
