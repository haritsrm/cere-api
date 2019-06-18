<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\ForumService;
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

    public function create($course_id, Request $req)
    {
        $result = $this->forum->create([
            'course_id' => $course_id,
            'body' => $req->body,
            'user_id' => $req->user()->id,
            'forum_id' => $req->forum_id,
        ]);

        return (new ForumResource($result))
        ->additional([
            'status' => true,
            'message' => 'Succesfully post'
        ]);
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
