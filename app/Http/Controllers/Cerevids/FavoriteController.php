<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\FavoriteService;
use App\Http\Resources\Favorite\FavoriteResource;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->favorite = new FavoriteService;
    }

    public function index($course_id)
    {
        $favorites = $this->favorite->browse($course_id);

        return FavoriteResource::collection($favorites);
    }

    public function create($course_id, Request $req)
    {
        $result = $this->favorite->create([
            'course_id' => $course_id,
            'student_id' => $req->student_id,
        ]);

        return new FavoriteResource($result);
    }

    public function find($course_id, $favorite_id)
    {
        $favorite = $this->favorite->find($favorite_id);

        return new FavoriteResource($favorite);
    }

    public function delete($course_id, $favorite_id)
    {
        $result = $this->favorite->destroy($favorite_id);

        return $result;
    }
}
