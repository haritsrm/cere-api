<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\CerevidService;
use App\Http\Resources\Cerevid\CerevidResource;

class CerevidController extends Controller
{
    public function __construct()
    {
        $this->cerevid = new CerevidService;
    }

    public function index($course_id)
    {
        $cerevids = $this->cerevid->browse($course_id);

        return CerevidResource::collection($cerevids);
    }

    public function create($course_id, Request $req)
    {
        $result = $this->cerevid->create([
            'course_id' => $course_id,
            'student_id' => $req->user()->id,
        ]);

        return (new CerevidResource($result))
        ->additional([
            'status' => true,
            'message' => 'Succesfully add cerevid'
        ]);
    }

    public function find($course_id, $cerevid_id)
    {
        $cerevid = $this->cerevid->find($cerevid_id);

        return new CerevidResource($cerevid);
    }

    public function delete($course_id, $cerevid_id)
    {
        $result = $this->cerevid->destroy($cerevid_id);

        return $result;
    }
}
