<?php

namespace App\Http\Controllers\Cerelisasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cerelisasi;
use App\Models\Department;

class CerelisasiController extends Controller
{
    public function analysis(Request $req)
    {
        if ($this->isFoundData($req)) {
            $this->clearAnalyticsData($req);
        }
        $this->createUserInfo($req);

        return $this->analyticsResult($req);
    }

    public function analyticsResult(Request $req)
    {
        $department_ranks = [];
        $countables = Cerelisasi::where('user_id', $req->user()->id)->get();
        foreach ($countables as $key => $countable) {
            $department = Department::find($countable->department_id);
            $passing_grade = $department->passing_grade;
            $average_point = Cerelisasi::where('department_id', $countable->department_id)->avg('total_point');
            $maximum_value = Cerelisasi::where('department_id', $countable->department_id)->max('total_point');
            $surveyor_count = Cerelisasi::where('department_id', $countable->department_id)->count();
            if ($countable->total_point < $passing_grade) {
                $countable->update(['status' => 'rendah']);
            }
            else if ($countable->total_point > $passing_grade && $countable->total_point < $average_point) {
                $countable->update(['status' => 'sedang']);
            }
            else {
                $countable->update(['status' => 'tinggi']);
            }

            array_push($department_ranks, [
                    'department' => [
                        'id' => $department->id,
                        'name' => $department->name,
                        'interrested_num' => $department->interrested_num,
                        'capacity' => $department->capacity,
                        'passing_grade' => $passing_grade,
                        'average_point' => round($average_point),
                        'maximum_value' => $maximum_value,
                        'tightness' => round($department->interrested_num/$department->capacity),
                    ],
                    'accuracy' => ($surveyor_count >= $department->interrested_num ? 90 : round($surveyor_count/$department->interrested_num)),
                    'ranks' => $this->getDepartmentRanking($req, $department->id),
                    'status' => $countable->status,
                ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'national_ranks' => $this->getNationalRanking($req),
                'department_ranks' => $department_ranks,
                'my_point' => $countable->total_point,
            ]
        ]);
    }

    public function getNationalRanking($req)
    {
        $i = 1;
        $my_rank = 0;
        $other_ranks = [];
        $rankings = Cerelisasi::groupBy('user_id')->orderBy('total_point', 'desc')->get();

        foreach ($rankings as $key => $ranking) {
            if ($ranking->user_id == $req->user()->id) {
                $my_rank = $i;
                if ($my_rank > 5) {
                    $j = $my_rank-5;
                    $array_ranks = Cerelisasi::groupBy('user_id')->orderBy('total_point', 'desc')->skip($my_rank-5)->take(11)->get();
                    foreach ($array_ranks as $key => $array_rank) {
                        array_push($other_ranks, [
                            'rank' => $j,
                            'total_point' => $array_rank->total_point
                        ]);
                        $j++;
                    }
                }
                else {
                    $j = 1;
                    $array_ranks = Cerelisasi::groupBy('user_id')->orderBy('total_point', 'desc')->take($my_rank+5)->get();
                    foreach ($array_ranks as $key => $array_rank) {
                        array_push($other_ranks, [
                            'rank' => $j,
                            'total_point' => $array_rank->total_point
                        ]);
                        $j++;
                    }
                }
            }
            $i++;
        }

        return [
            'my_rank' => $my_rank,
            'other_ranks' => $other_ranks,
        ];
    }

    public function getDepartmentRanking($req, $department_id)
    {
        $i = 1;
        $my_rank = 0;
        $other_ranks = [];
        $rankings = Cerelisasi::where('department_id', $department_id)->orderBy('total_point', 'desc')->get();

        foreach ($rankings as $key => $ranking) {
            if ($ranking->user_id == $req->user()->id) {
                $my_rank = $i;if ($my_rank > 5) {
                    $j = $my_rank-5;
                    $array_ranks = Cerelisasi::groupBy('user_id')->orderBy('total_point', 'desc')->skip($my_rank-5)->take(11)->get();
                    foreach ($array_ranks as $key => $array_rank) {
                        array_push($other_ranks, [
                            'rank' => $j,
                            'total_point' => $array_rank->total_point
                        ]);
                        $j++;
                    }
                }
                else {
                    $j = 1;
                    $array_ranks = Cerelisasi::groupBy('user_id')->orderBy('total_point', 'desc')->take($my_rank+5)->get();
                    foreach ($array_ranks as $key => $array_rank) {
                        array_push($other_ranks, [
                            'rank' => $j,
                            'total_point' => $array_rank->total_point
                        ]);
                        $j++;
                    }
                }
            }
            $i++;
        }

        return [
            'my_rank' => $my_rank,
            'other_ranks' => $other_ranks,
        ];
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

    public function clearAnalyticsData($req)
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
