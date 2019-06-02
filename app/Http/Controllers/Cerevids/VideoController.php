<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\VideoService;
use App\Models\LastSeen;
use App\Http\Resources\Video\VideoResource;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->video = new VideoService;
    }

    public function index($section_id)
    {
        $videos = $this->video->browse($section_id);

        return VideoResource::collection($videos);
    }

    public function create($section_id, Request $req)
    {
        $result = $this->video->create([
            'section_id' => $section_id,
            'title' => $req->title,
            'video_url' => $req->video_url
        ]);

        return (new VideoResource($result))->additional([
            'status' => true,
            'message' => 'Succesfully add favorite'
        ]);
    }

    public function lastSeen($id, $user_id)
    {
        $lastSeen = LastSeen::where('video_id', $id)->where('user_id', $user_id)->first();
        if (!is_null($lastSeen)) {
            $lastSeen->touch();
        }
        else {
            LastSeen::create([
                'video_id' => $id,
                'user_id' => $user_id
            ]);
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function find($section_id, $video_id, Request $req)
    {
        $video = $this->video->find($video_id);
        $this->lastSeen($video_id, $req->user()->id);

        return new VideoResource($video);
    }

    public function update($section_id, $video_id, Request $req)
    {
        $result = $this->video->update($video_id, [
            'title' => $req->title,
            'video_url' => $req->video_url
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully update '.$result->title
        ]);;
    }

    public function delete($section_id, $video_id)
    {
        $result = $this->video->destroy($video_id);

        return response()->json([
            'status' => true,
            'message' => 'Successfully delete section'
        ]);;
    }
}
