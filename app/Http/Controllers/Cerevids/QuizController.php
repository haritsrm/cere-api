<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\QuizService;
use App\Http\Resources\Quiz\QuizResource;
use App\Models\LastSeen;
use App\Models\Quiz;

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
        $result = Quiz::create([
            'title' => $req->title,
            'section_id' => $section_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil tambah quiz'
        ]);
    }

    public function createQuestion($quiz_id, Request $req)
    {
        $result = $this->quiz->create([
            'quiz_id' => $quiz_id,
            'question' => $req->question,
            'option_a' => $req->option_a,
            'option_b' => $req->option_b,
            'option_c' => $req->option_c,
            'option_d' => $req->option_d,
            'correct_answer' => $req->correct_answer,
        ]);

        return new QuizResource($result);
    }

    public function lastSeen($id, $user_id)
    {
        $lastSeen = LastSeen::where('quiz_id', $id)->where('user_id', $user_id)->first();
        if (!is_null($lastSeen)) {
            $lastSeen->touch();
        }
        else {
            LastSeen::create([
                'quiz_id' => $id,
                'user_id' => $user_id
            ]);
        }

        return response()->json([
            'status' => true
        ]);
    }

    public function find($section_id, $quiz_id, Request $req)
    {
        $quiz = $this->quiz->find($quiz_id);
        $this->lastSeen($quiz_id, $req->user()->id);

        return new QuizResource($quiz);
    }

    public function updateQuestion($quiz_id, $question_id, Request $req)
    {
        $result = $this->quiz->update($question_id, [
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

    public function update($section_id, $quiz_id, Request $req)
    {
        $result = Quiz::find($quiz_id)->update([
            'title' => $req->title,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil update detail quiz'
        ]);
    }

    public function delete($section_id, $quiz_id)
    {
        $result = $this->quiz->destroy($quiz_id);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus quiz'
        ]);
    }
}
