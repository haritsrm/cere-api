<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\FavoriteService;
use App\Http\Resources\Favorite\FavoriteResource;
use App\User;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->favorite = new FavoriteService;
    }

    public function index(Request $req)
    {
        $favorites = User::find($req->user()->id)->favorites;

        return FavoriteResource::collection($favorites);
    }

    public function create($course_id, Request $req)
    {
        $result = $this->favorite->create([
            'course_id' => $course_id,
            'user_id' => $req->user()->id,
        ]);

        return (new FavoriteResource($result))->additional([
            'status' => true,
            'message' => 'Succesfully add favorite'
        ]);
    }

    public function find($course_id, $favorite_id)
    {
        $favorite = $this->favorite->find($favorite_id);

        return new FavoriteResource($favorite);
    }

    public function delete($course_id, $favorite_id)
    {
        $result = $this->favorite->destroy($favorite_id);

        return response()->json([
            'status' => true,
            'message' => 'Successfully remove favorite',
        ], 201);
    }
}
