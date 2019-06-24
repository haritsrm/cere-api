<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttemptTryout;
use App\Models\Tryout;
use Carbon\Carbon;
use App\Http\Resources\Tryout\TryoutCollection;
use App\Http\Resources\Tryout\TryoutUserCollection;

class AttemptTryoutController extends Controller
{
    //
    public function getTryoutUser($id){
    	$today =  Carbon::now()->todatestring();
    	$data = AttemptTryout::join('tryouts','tryouts.id','=','attempt_tryouts.tryout_id')
    		->select('tryouts.*', 'attempt_tryouts.user_id as user_id', 'attempt_tryouts.left_attempt')
    		->where('attempt_tryouts.user_id','=',$id)
    		->where('tryouts.end_date','>=',$today)
    		->get();
    	return TryoutUserCollection::collection($data);	
    }

    public function getTryoutUserClass($id, Request $req){
        $today =  Carbon::now()->todatestring();
        $data = AttemptTryout::join('tryouts','tryouts.id','=','attempt_tryouts.tryout_id')
            ->select('tryouts.*', 'attempt_tryouts.user_id as user_id', 'attempt_tryouts.left_attempt')
            ->where('attempt_tryouts.user_id','=',$req->user()->id)
            ->where('tryouts.class_id','=',$id)
            ->where('tryouts.end_date','>=',$today)
            ->get();
        return TryoutUserCollection::collection($data); 
    }

    public function getExpireTryoutUser($id){
    	$today =  Carbon::now()->todatestring();
    	$data = AttemptTryout::join('tryouts','tryouts.id','=','attempt_tryouts.tryout_id')
    		->select('tryouts.*', 'attempt_tryouts.user_id as user_id')
    		->where('attempt_tryouts.user_id','=',$id)
    		->where('tryouts.end_date','<=',$today)
    		->get();
    	return TryoutCollection::collection($data);	
    }

    public function getExpireTryoutUserClass($id, Request $req){
        $today =  Carbon::now()->todatestring();
        $data = AttemptTryout::join('tryouts','tryouts.id','=','attempt_tryouts.tryout_id')
            ->select('tryouts.*', 'attempt_tryouts.user_id as user_id')
            ->where('attempt_tryouts.user_id','=',$req->user()->id)
            ->where('tryouts.class_id','=',$id)
            ->where('tryouts.end_date','<=',$today)
            ->get();
        return TryoutCollection::collection($data); 
    }

}
