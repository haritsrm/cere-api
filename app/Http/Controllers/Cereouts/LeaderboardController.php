<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cereout;
use App\Models\Tryout;
use DB;
use App\Http\Resources\Leaderboard\LeaderboardClassResource;
use App\Http\Resources\Leaderboard\LeaderboardLessonResource;

class LeaderboardController extends Controller
{
    //
    public function getLeaderboardByClass($id){
    	$data = Cereout::join('tryouts','tryouts.id','=','cereouts.tryout_id')
    			->select('tryouts.class_id','cereouts.score as score','cereouts.user_id as user_id')		
    			->where('tryouts.class_id','=',$id)
    			->orderBy('score', 'DESC')
    			->groupBy('cereouts.user_id')
    			->get();
    	return LeaderboardClassResource::collection($data);			
    }

    public function getLeaderboardByLesson($id){
    	$data = Cereout::join('tryouts','tryouts.id','=','cereouts.tryout_id')
    			->select('tryouts.lesson_id','cereouts.score as score','cereouts.user_id as user_id')
    			->where('tryouts.lesson_id','=',$id)
    			->orderBy('score', 'DESC')
    			->groupBy('cereouts.user_id')
    			->get();
    	return LeaderboardLessonResource::collection($data);			
    }
}
