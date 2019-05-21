<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\QuizService;
use App\Http\Resources\Quiz\QuizResource;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->quiz = new QuizService;
    }

    public function index($section_id)
    {
        $quiz = $this->quiz->browse($section_id);

        return QuizResource::collection($quiz);
    }

    public function create($section_id, Request $req)
    {
        $result = $this->quiz->create([
            'section_id' => $section_id,
            'question' => $req->question,
            'option_1' => $req->option_1,
            'option_2' => $req->option_2,
            'option_3' => $req->option_3,
            'option_4' => $req->option_4,
            'option_5' => $req->option_5,
            'answer' => $req->answer,
        ]);

        return new QuizResource($result);
    }

    public function find($section_id, $quiz_id)
    {
        $quiz = $this->quiz->find($quiz_id);

        return new QuizResource($quiz);
    }

    public function update($section_id, $quiz_id, Request $req)
    {
        $result = $this->quiz->update($quiz_id, [
            'question' => $req->question,
            'option_1' => $req->option_1,
            'option_2' => $req->option_2,
            'option_3' => $req->option_3,
            'option_4' => $req->option_4,
            'option_5' => $req->option_5,
            'answer' => $req->answer,
        ]);

        return $result;
    }

    public function delete($section_id, $quiz_id)
    {
        $result = $this->quiz->destroy($quiz_id);

        return $result;
    }
}
