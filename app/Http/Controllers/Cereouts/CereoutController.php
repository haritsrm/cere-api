<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cereouts\CereoutService;
use App\Http\Controllers\Services\Cereouts\AnswerService;
use App\Http\Controllers\Services\Cereouts\TryoutService;
use App\Http\Resources\Cereout\CereoutResource;
use App\User;
use App\Models\AttemptTryout;

class CereoutController extends Controller
{
    public function __construct()
    {
        $this->cereout = new CereoutService;
        $this->tryout = new TryoutService;
        $this->answer = new AnswerService;
    }

    public function index($tryout_id)
    {
        $cereouts = $this->cereout->browse($tryout_id);

        return CereoutResource::collection($cereouts);
    }

    public function indexByUser($tryout_id, Request $req)
    {
        $user_id = $req->user()->id;
        $cereouts = $this->cereout->browseByUser($tryout_id, $user_id);

        return CereoutResource::collection($cereouts);
    }

    public function ranking($tryout_id)
    {
        $rankings = $this->cereout->ranking($tryout_id);

        return $rankings;
    }

    public function attempt($tryout_id, Request $req)
    {
        $attempted_count = AttemptTryout::where('tryout_id', $tryout_id)
                        ->where('user_id', $req->user_id)
                        ->count();                
        $available_attempts = $this->tryout->find($tryout_id)->attempt_count;
        $price = $this->tryout->find($tryout_id)->price;
        $user = User::where('id',$req->user_id)->first();
        
        if($attempted_count==0 ){
            return response()->json([
                        'status' => false,
                        'message' => 'You have never been attempt this tryout'
                    ]);      
        }else{
            $check_attempted = AttemptTryout::where('tryout_id', $tryout_id)
                        ->where('user_id', $req->user_id)
                        ->first();
            if($check_attempted->left_attempt > 0){
                $result = $this->cereout->create([
                        'tryout_id' => $tryout_id,
                        'user_id' => $req->user_id
                    ]);

                $check_attempted->left_attempt -= 1;
                $check_attempted->save();

                return (new CereoutResource($result))
                            ->additional([
                                'status' => true,
                                'message' => 'Succesfully attempt a tryout'
                            ]);
            }else{
                return response()->json([
                        'status' => false,
                        'message' => 'You have maximum limit of attempt'
                    ]);
            }                
                
        }
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
        $score = 0;
        $answers = $this->answer->findCereout($id);
        foreach($answers as $answer){
            $correct_score = $answer->correct_score;
            $incorrect_score = $answer->incorrect_score;
            if(!is_null($answer->answer)){
                if($answer->answer == $this->question->find($answer->question_id)->correct_answer){
                    $correct_answered++;
                    $score += $correct_score;
                }
                else{
                    $incorrect_anwered++;
                    $score -= $incorrect_score;
                }
            }
            else{
                $left_answerd++;
            }
        }
        $passing_percentage = Question::join('tryouts','tryouts.id','=','question.tryout_id')
                    ->join('lessons','lessons.id','=','tryouts.lesson_id')
                    ->select('lessons.passing_percentage')
                    ->first(); 
        if($score > $passing_percentage){
            $result_status = "Lulus";
        }else{
            $result_status = "Tidak Lulus";
        }

        $result = $this->cereout->update($id, [
            'my_time' => $req->my_time,
            'score' => $score,
            'total_answered' => count($req->answered),
            'correct_answered' => $correct_answered,
            'incorrect_answered' => $incorrect_answered,
            'left_answered' => $left_answered,
            'result_status' => $result_status
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ], 201);
    }

    public function delete($tryout_id, $id)
    {
        $result = $this->cereout->destroy($id);

        return response()->json([
            'status' => 'success',
            'message'=> 'Succesfully remove attempt',
        ], 201);
    }
}
