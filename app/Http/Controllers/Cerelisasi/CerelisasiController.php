<?php

namespace App\Http\Controllers\Cerelisasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cerelisasi;
use App\Models\Department;

class CerelisasiController extends Controller
{
    public function analyticsResult(Request $req)
    {
        if ($this->isFoundData($req)) {
            $this->clearAnalyticsData();
        }
        $this->createUserInfo($req);

        $department_ranks = [];
        $countables = Cerelisasi::where('user_id', $req->user()->id)->get();
        foreach ($countables as $key => $countable) {
            $department = Department::find($countable->department_id);
            $passing_grade = $department->passing_grade;
            $average_point = Cerelisasi::where('department_id', $countable->department_id)->avg();
            if ($countable->total_point < $passing_grade) {
                $countable->update(['status' => 'rendah']);
            }
            else if ($countable->total_point > $passing_grade && $countable->total_point < $average_point) {
                $countable->update(['status' => 'sedang']);
            }
            else {
                $countable->update(['status' => 'tinggi']);
            }

            array_push($department_ranks, [$department->name => $this->getDepartmentRanking($department->id)]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'national_rank' => $this->getNationalRanking(),
                'department_ranks' => $department_ranks,
            ]
        ]);
    }

    public function getNationalRanking()
    {
        $i = 1;
        $my_rank = 0;
        $rankings = Cerelisasi::orderBy('total_point', 'desc');

        foreach ($rankings as $key => $ranking) {
            if ($ranking->user_id == $req->user()->id) {
                $my_rank = $i;
            }
            $i++;
        }

        return $my_rank;
    }

    public function getDepartmentRanking($department_id)
    {
        $i = 1;
        $my_rank = 0;
        $rankings = Cerelisasi::where('department_id', $department_id)->orderBy('total_point', 'desc');

        foreach ($rankings as $key => $ranking) {
            if ($ranking->user_id == $req->user()->id) {
                $my_rank = $i;
            }
            $i++;
        }

        return $my_rank;
    }

    public function isFoundData($req)
    {
        if (!is_null(Cerelisasi::where('user_id', $req->user()->id)->get())) {
            return true;
        }
        else {
            return false;
        }
    }

    public function clearAnalyticsData()
    {
        $cerelisasi = Cerelisasi::where('user_id', $req->user()->id)->delete();
        return true;
    }

    public function createUserInfo($req)
    {
        $total_point = array_sum($req->points);
        foreach ($req->departments as $key => $department) {
            Cerelisasi::create([
                'user_id' => $req->user()->id,
                'department_id' => $department,
                'total_point' => $total_point,
            ]);
        }

        return true;
    }
}
