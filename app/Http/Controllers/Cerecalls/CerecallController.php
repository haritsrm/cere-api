<?php

namespace App\Http\Controllers\Cerecalls;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HistoryCall;
use App\Models\ReportTeacher;
use App\Models\TeacherLesson;
use App\Models\Chat;
use App\Http\Resources\Cerecall\HistoryCallResource;
use App\Http\Resources\Cerecall\AvailTeacherResource;
use App\Http\Resources\Cerecall\ChatResource;
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
        $price = GeneralInformation::all();
        foreach ($price as $price) {
            $cerecall_price = $price->cerecall_price;
        }
        $student = User::where('id','=',$request->student_id)->first();
        if($student->balance<$cerecall_price){
            return response()->json([
            'status' => false,
            'data' => [
                'message' => 'your money is not enough'
            ],
        ], 201);
        }else{
        	$data = new HistoryCall();
        	$data->student_id = $request->student_id;
        	$data->teacher_id = $request->teacher_id;
            $data->status = 1;
        	// $data->rating = $request->rating;
        	// $data->review = $request->review;
        	$data->save();
            return response()->json([
                'status' => true,
                'data' => [
                    'message' => $data
                ],
            ], 201);
        }
    }

    public function updateHistoryCall(Request $request, $id){
        $data = HistoryCall::where('id',$id)->first();
        $data->rating = $request->rating;
        $data->review = $request->review;
        $data->status = 4;
        $data->save();

        $price = GeneralInformation::all();
        $cerecall_price=0;
        foreach ($price as $price) {
            $cerecall_price = $price->cerecall_price;
        }
        $teacher = User::where('id','=',$request->teacher_id)->first();
        $teacher->balance += $cerecall_price;
        $teacher->save();

        $student = User::where('id','=',$request->student_id)->first();
        $student->balance -= $cerecall_price;
        $student->save();
        return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully update data',
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
            ->select('users.status as status','users.name as name','teacher_lesson.teacher_id as teacher_id','teacher_lesson.lesson_id as lesson_id')
            ->where('lesson_id',$id)
            ->where('status',1)
            ->get();
            return new AvailTeacherResource($data);
    }

    public function postChatByKonsultasi($id, Request $request){
        $request->validate([
            'sender' => 'required|integer'

        ]);
        $chat = Chat::create([
            'history_call_id' => $id,
            'sender' => $request->sender
        ]);

        if($request->is_image==1){
            $image = $request->file('content');
            if(empty($image)){
                $namaFile = "null";
            }else{
                $extension = $image->getClientOriginalExtension();
                $namaFile = url('/images/chat/'.$chat->id.'.'.$extension);
                $request->file('content')->move('images/chat/', $namaFile);
            }
        }else{
            $namaFile = $request->content;
        }
        $data = Chat::where('id','=',$chat->id)->first();
        $data->content = $namaFile;
        $data->save();
        return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully post data',
            ],
        ], 201);
    }

    public function getChatByKonsultasi($id){
        $data = Chat::where('history_call_id',$id)->get();
        return new ChatResource($data);
    }

    public function getRunningKonsultasiStudent(Request $request){
        $data = HistoryCall::where('status',2)
                ->where('student_id',$request->user()->id)
                ->get();

        return new HistoryCallResource($data);
    }

    public function getRunningKonsultasiTeacher(Request $request){
        $data = HistoryCall::where('status',2)
                ->where('teacher_id',$request->user()->id)
                ->get();

        return new HistoryCallResource($data);
    }

    public function updateStatusKonsultasi($id, Request $request){
        $data = HistoryCall::where('id',$id)->first();
        $data->status = $request->status;
        $data->save();
        return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully update status',
            ],
        ], 201);
    }
}
