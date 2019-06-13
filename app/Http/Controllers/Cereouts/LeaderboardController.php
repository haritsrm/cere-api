<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cereout;
use App\Models\Tryout;
use App\User;
use DB;
use App\Http\Resources\Leaderboard\LeaderboardClassResource;
use App\Http\Resources\Leaderboard\LeaderboardLessonResource;
use App\Http\Resources\Leaderboard\LeaderboardTryoutResource;
use App\Http\Resources\Leaderboard\ChartResource;

class LeaderboardController extends Controller
{
    //
    public function getLeaderboardByClass($id){
    	$data = Cereout::join('tryouts','tryouts.id','=','cereouts.tryout_id')
    			->select('tryouts.class_id as class_id','cereouts.score as scores','cereouts.user_id as user_id',DB::raw('(score) as scores'))
    			->where('class_id','=',$id)
    			->where('cereouts.score','!=',null)
    			->orderBy('scores', 'DESC')
    			->groupBy('user_id')
    			->get();
    	return LeaderboardClassResource::collection($data);			
    }

    public function getLeaderboardByLesson($id){
    	$data = Cereout::join('tryouts','tryouts.id','=','cereouts.tryout_id')
    			->select('tryouts.lesson_id as lesson_id','cereouts.score as scores','cereouts.user_id as user_id',DB::raw('(score) as scores'))
    			->where('lesson_id','=',$id)
    			->where('cereouts.score','!=',null)
    			->orderBy('scores', 'DESC')
    			->groupBy('user_id')
    			->get();
    	return LeaderboardLessonResource::collection($data);			
    }

	public function getRanking($id, Request $request){
    	$ranking = Cereout::join('tryouts','tryouts.id','=','cereouts.tryout_id')
    			->select('tryouts.class_id as class_id','cereouts.score as scores','cereouts.user_id as user_id',DB::raw('(score) as scores'))
    			->where('class_id','=',$id)
    			->where('cereouts.score','!=',null)
    			->orderBy('scores', 'DESC')
    			->groupBy('user_id')
    			->get();
    	$rank=0;
        foreach ($ranking as $ranking ) {
            $rank++;
            if($ranking->user_id==$request->user()->id){
                break;
            }
        }		
    	return response()->json([
            'status' => true,
            'rank' => $rank,
        ],201);			
    }    

    public function getTopTryout($id){
    	$data = Cereout::join('tryouts','tryouts.id','=','cereouts.tryout_id')
    			->select('tryouts.class_id as class_id','tryouts.name as name','cereouts.tryout_id',DB::raw('count(*) as counts'))
    			->where('class_id','=',$id)
    			->groupBy('tryout_id')
                ->orderBy('counts', 'desc')
                ->take(5)
    			->get();
    	return LeaderboardTryoutResource::collection($data);
    }

    public function getChartByClass($id){
    	$data = Tryout::where('class_id','=',$id)
    		->get();

    	return ChartResource::collection($data);
    }

    public function getChartByLesson($id){
    	$data = Tryout::where('lesson_id','=',$id)
    		->get();

    	return ChartResource::collection($data);
    }
}
