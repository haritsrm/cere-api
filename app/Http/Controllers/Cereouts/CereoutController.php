<?php

namespace App\Http\Controllers\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cereouts\CereoutService;
use App\Http\Resources\Cereout\CereoutResource;

class CereoutController extends Controller
{
    public function __construct()
    {
        $this->cereout = new CereoutService;
    }

    public function index($tryout_id)
    {
        $cereouts = $this->cereout->browse($tryout_id);

        return CereoutResource::collection($cereouts);
    }

    public function attempt($tryout_id, Request $req)
    {
        $result = $this->cereout->create([
            'tryout_id' => $tryout_id,
            'student_id' => $req->student_id
        ]);

        return new CereoutResource($result);
    }

    public function find($tryout_id, $id)
    {
        $cereout = $this->cereout->find($id);

        return new CereoutResource($cereout);
    }

    public function valuation($tryout_id, $id, Request $req)
    {
        $passing_status = "Lulus";

        $result = $this->review->update($id, [
            'point' => $req->point,
            'passing_status' => $passing_status
        ]);

        return $result;
    }

    public function delete($tryout_id, $id)
    {
        $result = $this->cereout->destroy($id);

        return $result;
    }
}
