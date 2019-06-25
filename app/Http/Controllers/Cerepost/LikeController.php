<?php

namespace App\Http\Controllers\Cerepost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Http\Resources\Cerepost\LikeResource;

class LikeController extends Controller
{
    //
    public function index($id){
    	$data = Like::where('post_id',$id)->get();
    	return LikeResource::collection($data);
    }

    public function store($id, Request $req){
    	$likes = Like::where('post_id',$id)->where('user_id',$req->user()->id)->first();
    	if(is_null($likes)){
	    	$like = Like::create([
	    		'post_id' => $id,
	    		'user_id' => $req->user()->id
	    	]);

	    	return response()->json([
	            'status' => true,
	            'data' => [
	                'message' => 'succesfully like post',
	            ],
	        ], 201);
	    }else{
	    	return response()->json([
	            'status' => false,
	            'data' => [
	                'message' => 'you have liked this post',
	            ],
	        ], 201);
	    }
    }

    public function destroy($id, Request $req){
    	$like = Like::where('post_id',$id)->where('user_id',$req->user()->id)->first();
    	if(!is_null($like)){
	    	$like->delete();
	    	return response()->json([
	            'status' => true,
	            'data' => [
	                'message' => 'succesfully unlike post',
	            ],
	        ], 201);	
	    }else{
	    	return response()->json([
	            'status' => false,
	            'data' => [
	                'message' => 'you have not liked this post',
	            ],
	        ], 201);
	    }
    }
}
