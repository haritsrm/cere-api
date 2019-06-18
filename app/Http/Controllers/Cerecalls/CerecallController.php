<?php

namespace App\Http\Controllers\Cerecalls;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HistoryCall;
use App\Models\ReportTeacher;
use App\Models\TeacherLesson;
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
    	// $data->rating = $request->rating;
    	// $data->review = $request->review;
    	$data->save();

    	$price = GeneralInformation::where('id','=',1)->first();

    	// $teacher = User::where('id','=',$request->teacher_id)->first();
    	// $teacher->balance += $price->cerecall_price;
    	// $teacher->save();
    	return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully post data',
            ],
        ], 201);
    }

    public function UpdateHistoryCall(Request $request, $id){
        $data = HistoryCall::where('id',$id)->first();
        $data->rating = $request->rating;
        $data->review = $request->review;
        $data->save();

        $price = GeneralInformation::all();
        foreach ($price as $price) {
            $teacher = User::where('id','=',$data->teacher_id)->first();
            $teacher->balance += $price->cerecall_price;
            $teacher->save();
        }
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
            $namaFile = url('/images/cerecall/'.$report->id.'.'.$extension);
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

    public function getHistoryStudent(Request $request){
        $data = HistoryCall::where('student_id','=',$request->user()->id)->get();
        return new HistoryCallResource($data);
    }

    public function getAvailableTeacher($id){
        $data = TeacherLesson::join('users','users.id','=','teacher_lesson.teacher_id')
            ->select('users.status','users.name','teacher_lesson.teacher_id','teacher_lesson.lesson_id')
            ->where('teacher_lesson.lesson_id',$id)
            ->where('users.status',1)
            ->get();
    }

    public function postChatByKonsultasi($id){
        $request->validate([
            'history_call_id' => 'required|integer',
            'sender' => 'required|integer',
            'content' => 'required|string'

        ]);
        $data = new Chat();
        $data->history_call_id = $request->history_call_id;
        $data->sender = $request->sender;
        $data->content = $request->content;
        // $data->review = $request->review;
        $data->save();
    }

    public function getChatByKonsultasi($id){
        $data = Chat::where('history_call_id',$id)->get();
    }

    public function getRunningKonsultasi(Request $request){
        $data = HistoryCall::where('status',2)->get();
    }

    public function updateStatusKonsultasi($id, Request $request){
        $data = HistoryCall::where('id',$id)->first();
        $data->status = $request->status;
        $data->save();
    }
}
