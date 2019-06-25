<?php

namespace App\Http\Controllers\Cerepost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\Cerepost\PostResource;

class PostController extends Controller
{
    //
    public function index(){
    	$data = Post::OrderBy('created_at','DESC')->get();
    	return PostResource::collection($data);
    }

    public function indexByCerepost($id){
    	$data = Post::where('cerepost_id',$id)->OrderBy('created_at','DESC')->get();
    	return PostResource::collection($data);
    }

    public function store(Request $req, $id){
    	$req->validate([
            'title' => 'required|string',
            'content' => 'required|string'
        ]);
    	$post = Post::create([
    		'cerepost_id' => $id,
    		'title' => $req->title,
    		'content' => $req->content,
    		'user_id' => $req->user()->id
    	]);

    	$image = $req->file('image');
        if(empty($image)){
            $namaFile = null;
        }else{
            $extension = $image->getClientOriginalExtension();
            $namaFile = url('/images/cerepost/'.$post->id.'.'.$extension);
            $req->file('image')->move('images/cerepost/', $namaFile);
        }

        $data = Post::where('id','=',$post->id)->first();
    	$data->image = $namaFile;
    	$data->save();

    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully post data',
            ],
        ], 201);
    }

    public function show($id){
    	$post = Post::where('id',$id)->get();
    	return PostResource::collection($post);
    }

    public function update($id,Request $req){
    	$req->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'cerepost_id' => 'required|string'
        ]);

        $data = Post::where('id','=',$id)->first();
    	$data->title = $req->title;
    	$data->content = $req->content;
    	$data->save();

    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully update data',
            ],
        ], 201);
    }

    public function updatePhoto($id, Request $req){
    	$image = $req->file('image');
        if(empty($image)){
            $namaFile = null;
        }else{
            $extension = $image->getClientOriginalExtension();
            $namaFile = url('/images/cerepost/'.$post->id.'.'.$extension);
            $req->file('image')->move('images/cerepost/', $namaFile);
        }

        $data = Post::where('id','=',$id)->first();
    	$data->image = $namaFile;
    	$data->save();

        return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully update photo',
            ],
        ], 201);
    }

    public function destroy($id){
    	$post = Post::where('id',$id)->first();
    	$post->delete();

    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully delete data',
            ],
        ], 201);
    }
}
