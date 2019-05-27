<?php

namespace App\Http\Controllers\Services\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Video;

class VideoService extends Controller
{
    public function __construct()
    {
        $this->newVideo = new Video;
        $this->newSection = new Section;
    }

    public function browse($course_id)
    {
        return $this->newSection->find($course_id)->videos()->get();
    }

    public function create(Array $req)
    {
        return $this->newVideo->create($req);
    }

    public function find($id)
    {
        return $this->newVideo->find($id);
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
