<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\LessonResource;
use App\Models\Lesson;

class LessonController extends Controller
{
    //
    public function index(){
    	$data = Lesson::join('classes','classes.id','=','lessons.class_id')
                    ->select('classes.*','classes.name as class')->get();
        return LessonResource::collection($data);
    }
}
