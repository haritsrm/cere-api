<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cereouts\QuestionService;
use App\Http\Resources\Question\QuestionResource;
use App\Models\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data = Question::join('tryouts','tryouts.id','=','questions.tryout_id')
                    ->select('questions.*','tryouts.duration','tryouts.name')
                    ->where('questions.tryout_id',$id)->get();

        return QuestionResource::collection($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

}
