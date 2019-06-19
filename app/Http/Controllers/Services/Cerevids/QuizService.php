<?php

namespace App\Http\Controllers\Services\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Quiz;
use App\Models\QuestionQuiz;

class QuizService extends Controller
{
    public function __construct()
    {
        $this->newQuiz = new Quiz;
        $this->newQuestion = new QuestionQuiz;
        $this->newSection = new Section;
    }

    public function browse($course_id)
    {
        return $this->newSection->find($course_id)->quiz()->get();
    }

    public function create(Array $req)
    {
        return $this->newQuestion->create($req);
    }

    public function find($id)
    {
        return $this->newQuiz->find($id);
    }

    public function update($id, Array $req)
    {
        $this->newQuestion->find($id)->update($req);
    }

    public function destroyQuestion($id)
    {
        $this->newQuestion->find($id)->delete();
    }

    public function destroy($id)
    {
        $this->find($id)->delete();
        $this->newQuestion->where('quiz_id', $id)->delete();
    }
}
