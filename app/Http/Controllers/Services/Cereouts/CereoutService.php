<?php

namespace App\Http\Controllers\Services\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cereout;
use App\Models\Tryout;

class CereoutService extends Controller
{
    public function __construct()
    {
        $this->newCereout = new Cereout;
        $this->newTryout = new Tryout;
    }

    public function browse($tryout_id)
    {
        return $this->newTryout->find($tryout_id)->cereouts()->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newCereout->create($req);
    }

    public function ranking($tryout_id)
    {
        return $this->newCereout->where('tryout_id', $tryout_id)->orderByDesc('score');
    }

    public function find($id)
    {
        return $this->newCereout->find($id);
    }

    public function findUser($user_id)
    {
        return $this->newCereout->where('user_id', $user_id)->get();
    }

    public function update($id, Array $req)
    {
        $this->find($id)->update($req);
    }

    public function destroy($id)
    {
        $this->find($id)->delete();
    }
}
