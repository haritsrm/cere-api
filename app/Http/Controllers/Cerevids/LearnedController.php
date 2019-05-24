<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Learned;
use App\Http\Resources\Learned\LearnedResource;

class LearnedController extends Controller
{
    //
    public function index($id){
    	$data = Learned::join('lessons','lessons.id','=','learneds.lesson_id')
                    ->select('learneds.*')
                    ->where('learneds.user_id',$id)
                    ->get();
        return LearnedResource::collection($data);
    }

    public function store(Request $request){
    	$user = new User([
            'lesson_id' => $request->lesson_id,
            'user_id' => $request->user_id
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Successfully created data!'
        ], 201);
    }
}
