<?php

namespace App\Http\Controllers\Cerecalls;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HistoryCall;
use App\Models\ReportTeacher;
use App\Http\Resources\Cerecall\HistoryCallResource;
use App\Models\GeneralInformation;
use App\User;
class CerecallController extends Controller
{
    //
    public function postHistoryCall(Request $request){
    	$request->validate([
            'student_id' => 'required|integer',
            'teacher_id' => 'required|integer'
        ]);
    	$data = new HistoryCall();
    	$data->student_id = $request->student_id;
    	$data->teacher_id = $request->teacher_id;
    	$data->rating = $request->rating;
    	$data->review = $request->review;
    	$data->save();

    	$price = GeneralInformation::where('id','=',1)->first();

    	$teacher = User::where('id','=',$request->teacher_id)->first();
    	$teacher->balance += $price->cerecall_price;
    	$teacher->save();
    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully post data',
            ],
        ], 201);
    }

    public function postReportTeacher(Request $request){
    	$request->validate([
            'student_id' => 'required|integer',
            'teacher_id' => 'required|integer'
        ]);

        $report = ReportTeacher::create([
            'student_id' => $request->student_id,
            'teacher_id' => $request->teacher_id,
            'report' => $request->report
        ]);

    	$image = $request->file('image_url');
        if(empty($image)){
            $namaFile = "null";
        }else{
            $extension = $image->getClientOriginalExtension();
            $namaFile = 'http://api.ceredinas.id/images/cerecall/'.$report->id.'.'.$extension;
            $request->file('image_url')->move('images/cerecall/', $namaFile);
        }

        $data = ReportTeacher::where('id','=',$report->id)->first();
    	$data->image_url = $namaFile;
    	$data->save();

    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully post data',
            ],
        ], 201);
    }

    public function changeStatus(Request $request){
    	$user =User::where('id','=',$request->user()->id)->first();
    	$user->status=$request->status;
    	$user->save();
    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully change status',
            ],
        ], 201);
    }

    public function getHistoryTeacher(Request $request){
    	$data = HistoryCall::where('teacher_id','=',$request->user()->id)->get();
    	return new HistoryCallResource($data);
    }
}
