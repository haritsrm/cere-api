<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cereouts\CereoutService;
use App\Http\Controllers\Services\Cereouts\AnswerService;
use App\Http\Resources\Cereout\CereoutResource;

class CereoutController extends Controller
{
    public function __construct()
    {
        $this->cereout = new CereoutService;
        $this->answer = new AnswerService;
    }

    public function index($tryout_id)
    {
        $cereouts = $this->cereout->browse($tryout_id);

        return CereoutResource::collection($cereouts);
    }

    public function attempt($tryout_id, Request $req)
    {
        $result = $this->cereout->create([
            'tryout_id' => $tryout_id,
            'user_id' => $req->user_id
        ]);

        return new CereoutResource($result);
    }

    public function find($tryout_id, $id)
    {
        $cereout = $this->cereout->find($id);

        return new CereoutResource($cereout);
    }

    public function valuation($tryout_id, $id, Request $req)
    {
        foreach($req->answered as $answer){
            $this->answer->create([
                'cereout_id' => $id,
                'question_id' => $answer->question_id,
                'answer' => $answer->answer
            ]);
        }

        $correct_answered = 0;
        $incorrect_answered = 0;
        $left_answered = 0;
        $answers = $this->answer->findCereout($id);
        foreach($answers as $answer){
            if(!is_null($answer->answer)){
                if($answer->answer == $this->question->find($answer->question_id)->correct_answer){
                    $correct_answered++;
                }
                else{
                    $incorrect_anwered++;
                }
            }
            else{
                $left_answerd++;
            }
        }
        $score = $correct_answered * 10;
        $result_status = "Lulus";

        $result = $this->cereout->update($id, [
            'my_time' => $req->my_time,
            'score' => $score,
            'total_answered' => count($req->answered),
            'correct_answered' => $correct_answered,
            'incorrect_answered' => $incorrect_answered,
            'left_answered' => $left_answered,
            'result_status' => $result_status
        ]);

        return $result;
    }

    public function delete($tryout_id, $id)
    {
        $result = $this->cereout->destroy($id);

        return $result;
    }
}
