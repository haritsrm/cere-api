<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Learned;
use App\User;
use App\Http\Resources\Learned\LearnedResource;

class LearnedController extends Controller
{
    //
    public function index($id){
    	$data = Learned::join('courses','courses.id','=','learneds.course_id')
    				->join('users','users.id','=','courses.user_id')
                    ->select('learneds.*', 'courses.title','courses.cover', 'courses.description' ,'courses.user_id','users.name')
                    ->where('learneds.user_id',$id)
                    ->get();
        return LearnedResource::collection($data);
    }

    public function store(Request $request){
    	$data = new Learned;
        $data->course_id = $request->course_id;
        $data->user_id = $request->user_id;
        $data->save();
        return response()->json([
            'status' => true,
            'message' => 'Successfully created data!'
        ], 201);
    }
}
