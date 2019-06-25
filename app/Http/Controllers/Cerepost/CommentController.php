<?php

namespace App\Http\Controllers\Cerepost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Resources\Cerepost\CommentResource;

class CommentController extends Controller
{
    //
    public function index($id){
    	$data = Comment::where('post_id',$id)->where('comment_id',null)->get();
    	return CommentResource::collection($data);
    }

    public function store($id, Request $req){
    	$req->validate([
            'content' => 'required|string'
        ]);

        $comment = Comment::create([
    		'post_id' => $id,
    		'content' => $req->content,
    		'user_id' => $req->user()->id
    	]);

    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully commented post',
            ],
        ], 201);
    }

    public function destroy($id){
    	$comment = Comment::where('id',$id)->first();
    	$comment->delete();
    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully delete data',
            ],
        ], 201);
    }
}
