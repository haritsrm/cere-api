<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cereouts\TryoutService;
use App\Http\Resources\Tryout\TryoutResource;
use App\Models\Question;
use App\Models\Tryout;
use App\Models\Lesson;

class TryoutController extends Controller
{
    public function __construct()
    {
        $this->tryout = new TryoutService;
    }

    public function index()
    {
        $tryouts = $this->tryout->browse();

        return TryoutResource::collection($tryouts);
    }

    public function create(Request $req)
    {
        $result = $this->tryout->create([
            'lesson_id' => $req->lesson_id, 
            'class_id' => $req->class_id, 
            'name' => $req->name, 
            'instruction' => $req->instruction, 
            'duration' => $req->duration,
            'attempt_count' => $req->attempt_count, 
            'start_date' => $req->start_date, 
            'end_date' => $req->end_date, 
            'price' => $req->price,
            'scoring_system' => $req->scoring_system
        ]);

        return new TryoutResource($result);
    }

    public function find($id)
    {
        $tryout = $this->tryout->find($id);

        return new TryoutResource($tryout);
    }

    public function update($id, Request $req)
    {
        $result = $this->tryout->update($id, [
            'lesson_id' => $req->lesson_id, 
            'class_id' => $req->class_id, 
            'name' => $req->name, 
            'instruction' => $req->instruction, 
            'duration' => $req->duration, 
            'attempt_count' => $req->attempt_count, 
            'start_date' => $req->start_date, 
            'end_date' => $req->end_date, 
            'price' => $req->price,
            'scoring_system' => $req->scoring_system
        ]);

        return new TryoutResource($result);
    }

    public function delete($id)
    {
        $result = $this->tryout->destroy($id);

        return $result;
    }
}
