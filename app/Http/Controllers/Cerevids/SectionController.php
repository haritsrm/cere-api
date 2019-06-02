<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\SectionService;
use App\Http\Resources\Section\SectionResource;
use App\Models\LastSeen;

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

        return (new SectionResource($result))->additional([
            'status' => true,
            'message' => 'Succesfully add favorite'
        ]);
    }

    public function lastSeen($id, Request $req)
    {
        $user_id = 2;
        $type = $req->type;

        $lastSeen = LastSeen::where($type.'_id', $id)->where('user_id', $user_id)->first();
        if (!is_null($lastSeen)) {
            $lastSeen->touch();
        }
        else {
            LastSeen::create([
                $type.'_id' => $id,
                'user_id' => $user_id
            ]);
        }

        return response()->json([
            'status' => true,
            $req
        ]);
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

        return response()->json([
            'status' => true,
            'message' => 'Successfully update '.$result->title
        ]);;
    }

    public function delete($course_id, $section_id)
    {
        $result = $this->section->destroy($section_id);

        return response()->json([
            'status' => true,
            'message' => 'Successfully delete section'
        ]);;
    }
}
