<?php

namespace App\Http\Controllers\Cerecalls;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HistoryCall;
use App\Models\ReportTeacher;
use App\Models\TeacherLesson;
use App\Models\Chat;
use DB;
use App\Http\Resources\Cerecall\HistoryCallResource;
use App\Http\Resources\Cerecall\AvailTeacherResource;
use App\Http\Resources\Cerecall\ChatResource;
use App\Models\GeneralInformation;
use App\User;
use App\Notifications\PushNotification;
use OneSignal;

class CerecallController extends Controller
{
    //1 post to teacher
    //2 reject to student
    //3 accept to student
    //4 chat 

    public function postHistoryCall(Request $request){
    	$request->validate([
            'student_id' => 'required|integer',
            'teacher_id' => 'required|integer',
            'lesson_id' => 'required|integer' 
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
            $data->lesson_id = $request->lesson_id;
            $data->status = 1;
        	// $data->rating = $request->rating;
        	// $data->review = $request->review;
            $student = User::where('id',$request->student_id)->first();
            $teacher = User::where('id',$request->teacher_id)->first();
        	$data->save();
            // $kirim = "cerebrum";
            $title = array(
                "en" => "Halo ".$teacher->name
            );

            $send = array(
                "status" => 1
            );
            $content = $student->name. " ingin berkonsultasi dengan anda";

            // $judul = "Halo ".$teacher->name;
            // $isi = $student->name. " ingin berkonsultasi dengan anda";
            // $user=User::where('id',$request->teacher_id)->first();
            // $user->notify(new PushNotification($user->device_id, $judul, $isi, 1)); 
            OneSignal::setParam('headings', $title)
                    ->sendNotificationToUser(
                $content,
                $teacher->device_id,
                $url = null,
                $data = $send,
                $buttons = null,
                $schedule = null
            );
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
        $teacher->status = 1;
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

    public function postReportTeacher(Request $request, $id){
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
        $data->history_call_id = $id;
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
    	$data = HistoryCall::where('teacher_id','=',$request->user()->id)
                ->where('status','!=',2)
                ->orderBy('created_at','DESC')->get();

    	return HistoryCallResource::collection($data);        
    }

    public function getHistoryStudent(Request $request){
        $data = HistoryCall::where('student_id','=',$request->user()->id)
                ->where('status','!=',2)
                ->orderBy('created_at','DESC')->get();

        return HistoryCallResource::collection($data);        
    }

    public function getAvailableTeacher($id){
        $data = TeacherLesson::join('users','users.id','=','teacher_lesson.teacher_id')
            ->select('users.status as status','users.name as name','teacher_lesson.teacher_id as teacher_id','teacher_lesson.lesson_id as lesson_id','users.photo_url as photo_url')
            ->where('teacher_lesson.lesson_id',$id)
            ->where('users.status',1)
            ->get();
        return AvailTeacherResource::collection($data);
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

        $title = array(
            "en" => $request->user()->name
        );

        $send = array(
                "status" => 4
            );
        OneSignal::setParam('headings', $title)
                    ->sendNotificationToUser(
            $request->content,
            $request->user()->device_id,
            $url = null,
            $data = $send,
            $buttons = null,
            $schedule = null
        );

        return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully post data',
            ],
        ], 201);
    }

    public function getChatByKonsultasi($id){
        $data = Chat::where('history_call_id',$id)->orderBy('created_at','ASC')->get();
        return new ChatResource($data);
    }

    public function getRunningKonsultasiStudent(Request $request){
        $data = HistoryCall::where('status',2)
                ->where('student_id',$request->user()->id)
                ->get();
        return HistoryCallResource::collection($data);        
    }

    public function getRunningKonsultasiTeacher(Request $request){
        $data = HistoryCall::where('status',2)
                ->where('teacher_id',$request->user()->id)
                ->get();
        return HistoryCallResource::collection($data);        
    }

    public function getConfirmConsultationTeacher(Request $request){
        $data = HistoryCall::where('status',1)
                ->where('teacher_id',$request->user()->id)
                ->get();
        return HistoryCallResource::collection($data);        
    }

    public function updateStatusKonsultasi($id, Request $request){
        $data = HistoryCall::where('id',$id)->first();
        $data->status = $request->status;
        $data->save();
        if($request->status == 2){
            $teacher_status = User::where('id',$request->user()->id)->first();
            $teacher_status->status = 0;
            $teacher_status->save();
            $history_call = HistoryCall::where('teacher_id',$request->user()->id)
                        ->where('status',1)
                        ->get();
            foreach ($history_call as $history_call) {
                $history_call->status = 3;
                $history_call->save();
            }

            $title = array(
                "en" => "Selamat ".$student->name
            );
            $send = array(
                "status" => 3
            );

            $content = $teacher_status->name." telah menerima konsultasi anda";
            OneSignal::setParam('headings', $title)
                    ->sendNotificationToUser(
                $content,
                $student->device_id,
                $url = null,
                $data = $send,
                $buttons = null,
                $schedule = null
            );
        }elseif($request->status == 3) {
            $teacher_status = User::where('id',$request->user()->id)->first();
            $teacher_status->status = 1;
            $teacher_status->save();
            $student = User::where('id',$data->student_id)->first();
            $title = array(
                "en" => "Mohon maaf ".$student->name
            );

            $send = array(
                "status" => 2
            );
            $content = $teacher_status->name." sudah menerima konsultasi lain";
            OneSignal::setParam('headings', $title)
                    ->sendNotificationToUser(
                $content,
                $student->device_id,
                $url = null, 
                $data = $send,
                $buttons = null,
                $schedule = null
            );
        }elseif($request->status == 4){
            $teacher_status = User::where('id',$request->user()->id)->first();
            $teacher_status->status = 1;
            $teacher_status->save();
        }
        return response()->json([
            'status' => true,
            'data' => [
                'message' => 'succesfully update status',
            ],
        ], 201);
    }

    public function getPerformanceTeacher(Request $request){
        $consultation = HistoryCall::where('teacher_id',$request->user()->id)->where('status',4)->get();
        if($request->user()->photo_url == null){
            $photo = null;
        }else{
            $photo = url('/images/student/'.$request->user()->photo_url);               
        }
        $rating = HistoryCall::select('rating',DB::raw('avg(rating) as rating'))
            ->where('teacher_id',$request->user()->id)
            ->where('status',4)
            ->first();
        return response()->json([
            'status' => true,
            'data' => [
                'photo_url' => $photo,
                'name' => $request->user()->name,
                'coin' => $request->user()->balance,
                'rating' => number_format((float)$rating->rating, 1, '.', ''),
                'number_consultation' => count($consultation),
            ],
        ], 201);
    }
}
