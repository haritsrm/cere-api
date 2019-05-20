<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\SectionService;
use App\Http\Resources\Section\SectionResource;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->section = new SectionService;
    }

    public function index($course_id)
    {
        $sections = $this->section->browse($course_id);

        return SectionResource::collection($sections);
    }

    public function create($course_id, Request $req)
    {
        $result = $this->section->create([
            'course_id' => $course_id,
            'title' => $req->title,
        ]);

        return new SectionResource($result);
    }

    public function find($course_id, $section_id)
    {
        $section = $this->section->find($section_id);

        return new SectionResource($section);
    }

    public function update($course_id, $section_id, Request $req)
    {
        $result = $this->section->update($section_id, [
            'title' => $req->title,
        ]);

        return $result;
    }

    public function delete($course_id, $section_id)
    {
        $result = $this->section->destroy($section_id);

        return $result;
    }
}
