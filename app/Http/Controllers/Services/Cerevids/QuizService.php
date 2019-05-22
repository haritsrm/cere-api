<?php

namespace App\Http\Controllers\Services\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Quiz;

class QuizService extends Controller
{
    public function __construct()
    {
        $this->newQuiz = new Quiz;
        $this->newSection = new Section;
    }

    public function browse($course_id)
    {
        return $this->newSection->find($course_id)->quiz()->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newQuiz->create($req);
    }

    public function find($id)
    {
        return $this->newQuiz->find($id);
    }

    public function update($id, Array $req)
    {
        $this->find($id)->update($req);
    }

    public function destroy($id)
    {
        $this->find($id)->delete();
    }
}
