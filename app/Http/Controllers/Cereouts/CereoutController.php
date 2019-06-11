<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cereouts\CereoutService;
use App\Http\Controllers\Services\Cereouts\AnswerService;
use App\Http\Controllers\Services\Cereouts\TryoutService;
use App\Http\Resources\Cereout\CereoutResource;
use App\Http\Resources\Cereout\DetailCereoutResource;
use App\Http\Resources\Cereout\SummaryCereoutResource;
use App\User;
use DB;
use App\Models\Question;
use App\Models\Cereout;
use App\Models\Answer;
use App\Models\AttemptTryout;

class CereoutController extends Controller
{
    public function __construct()
    {
        $this->cereout = new CereoutService;
        $this->tryout = new TryoutService;
        $this->answer = new AnswerService;
        $this->question = new Question;
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
        
        if($user->membership==false ){
            return response()->json([
                        'status' => false,
                        'message' => 'You are not member'
                    ]);      
        }else{
            if($attempted_count==0){
                $data = new AttemptTryout;
                $data->tryout_id = $tryout_id;
                $data->user_id = $req->user_id;
                $data->left_attempt = $available_attempts;
                $data->save();
            }
            $check_attempted = AttemptTryout::where('tryout_id', $tryout_id)
                        ->where('user_id', $req->user_id)
                        ->first();
            if($check_attempted->left_attempt > 0 ){
                
                $result = $this->cereout->create([
                        'tryout_id' => $tryout_id,
                        'user_id' => $req->user_id
                    ]);

                $check_attempted->left_attempt -= 1;
                $check_attempted->save();

                // return (new CereoutResource($result))
                //             ->additional([
                //                 'status' => true,
                //                 'message' => 'Succesfully attempt a tryout'
                //             ]);
                return response()->json([
                        'status' => true,
                        'message' => 'Succesfully attempt a tryout',
                        'data' => $result
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
        foreach($req->answers as $answer){
            $this->answer->create([
                'cereout_id' => $id,
                'question_id' => $answer['question_id'],
                'mark' => $answer['mark'],
                'answer' => $answer['answer']
                
            ]);
        }

        $correct_answered = 0;
        $incorrect_answered = 0;
        $left_answered = 0;
        $score = 0;
        $answers = $this->answer->findCereout($id);
        foreach($answers as $answer){
            $correct_score = $this->question->find($answer->question_id)->correct_score;
            $score_question_true = $this->question->find($answer->question_id)->correct_score;
            $incorrect_score = $this->question->find($answer->question_id)->incorrect_score;
            $score_question_false = $this->question->find($answer->question_id)->incorrect_score;
            if(!is_null($answer->answer)){
                if($answer->answer == $this->question->find($answer->question_id)->correct_answer){
                    $correct_answered++;
                    $score += $correct_score;
                    $check_answer = Answer::where('cereout_id','=',$id)
                        ->where('question_id','=',$answer->question_id)
                        ->first();
                    $check_answer->check_answer = 1;
                    $check_answer->score = $score_question_true;
                    $check_answer->save();
                }
                else{
                    $incorrect_answered++;
                    $score += $incorrect_score;
                    $check_answer = Answer::where('cereout_id','=',$id)
                        ->where('question_id','=',$answer->question_id)
                        ->first();
                    $check_answer->check_answer = 0;
                    $check_answer->score = $score_question_false;
                    $check_answer->save();
                }
            }
            else{
                $left_answered++;
                $check_answer = Answer::where('cereout_id','=',$id)
                        ->where('question_id','=',$answer->question_id)
                        ->first();
                    $check_answer->check_answer = 0;
                    $check_answer->score = 0;
                    $check_answer->save();
            }
        }
        $passing_percentage = Question::join('tryouts','tryouts.id','=','questions.tryout_id')
                    ->join('lessons','lessons.id','=','tryouts.lesson_id')
                    ->select('lessons.passing_percentage')
                    ->first()->passing_percentage; 
        if($score > $passing_percentage){
            $result_status = "Lulus";
        }else{
            $result_status = "Tidak Lulus";
        }

        $result = $this->cereout->update($id, [
            'my_time' => $req->my_time,
            'score' => $score,
            'total_answer' => count($req->answers),
            'correct_answered' => $correct_answered,
            'incorrect_answered' => $incorrect_answered,
            'left_answered' => $left_answered,
            'result_status' => $result_status
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'score' => $score,
                'correct_answered' => $correct_answered,
                'incorrect_answered' => $incorrect_answered,
                'left_answered' => $left_answered,
                'result_status' => $result_status
            ],
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

    public function getCereoutByUser($id){
        $data = Cereout::where('user_id','=',$id)
                ->orderBy('created_at','DESC')
                ->get();
        return CereoutResource::collection($data);        
    }

    public function getDetailCereoutByUser($id){
        $data = Answer::join('questions','questions.id','=','answers.question_id')
            ->select('answers.*','questions.explanation as explanation', 'questions.question as question','questions.url_explanation as url_explanation', 'questions.option_a as option_a' , 'questions.option_b as option_b', 'questions.option_c as option_c', 'questions.option_d as option_d', 'questions.option_e as option_e', 'questions.option_f as option_f', 'questions.correct_answer as correct_answer')
            ->where('answers.cereout_id','=',$id)
            ->get();
        return DetailCereoutResource::collection($data);    
    }

    public function getSummaryTryout($id,$tryout_id,$user_id){
        $ranking = Cereout::select('user_id','score','tryout_id',DB::raw('max(score) as score'))
                ->orderBy('score', 'DESC')
                ->groupBy('user_id')
                ->where('tryout_id','=',$tryout_id)
                ->get();
        $userTryout = Cereout::groupBy('user_id')
                ->where('tryout_id','=',$tryout_id)
                ->get();
        $cereout = Cereout::where('user_id','=',$user_id)
                ->where('tryout_id','=',$tryout_id)
                ->where('id','=',$id)
                ->first();
        $rank=0;
        foreach ($ranking as $ranking ) {
            $rank++;
            if($ranking->user_id==$user_id){
                break;
            }
        }

        return response()->json([
            'status' => true,
            'time' => $cereout->my_time,
            'score' => $cereout->score,
            'correct_answered' => $cereout->correct_answered,
            'incorrect_answered' => $cereout->incorrect_answered,
            'left_answered' => $cereout->left_answered,
            'result_status' => $cereout->result_status,
            'tryout_ranking' => $rank,
            'tryout_user' => count($userTryout)
        ],201);
    }    
}
