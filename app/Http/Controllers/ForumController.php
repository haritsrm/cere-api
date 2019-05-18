<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Services\ForumService;
use App\Http\Resources\Forum\ForumResource;

class ForumController extends Controller
{
    public function __construct()
    {
        $this->forum = new ForumService;
    }

    public function index($course_id)
    {
        $forums = $this->forum->browse($course_id);

        return ForumResource::collection($forums);
    }

    public function createForStudent($course_id, Request $req)
    {
        $result = $this->forum->create([
            'course_id' => $course_id,
            'body' => $req->body,
            'student_id' => $req->student_id,
        ]);

        return new ForumResource($result);
    }

    public function createForTeacher($course_id, Request $req)
    {
        $result = $this->forum->create([
            'course_id' => $course_id,
            'body' => $req->body,
            'teacher_id' => $req->teacher_id,
        ]);

        return new ForumResource($result);
    }

    public function find($course_id, $forum_id)
    {
        $forum = $this->forum->find($forum_id);

        return new ForumResource($forum);
    }

    public function update($course_id, $forum_id, Request $req)
    {
        $result = $this->forum->update($forum_id, [
            'body' => $req->body,
        ]);

        return $result;
    }

    public function delete($course_id, $forum_id)
    {
        $result = $this->forum->destroy($forum_id);

        return $result;
    }
}
