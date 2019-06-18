<?php

namespace App\Http\Resources\Cereout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Cereout;
class MyTryoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cereout = Cereout::where('tryout_id',$this->id)->count();
        return [       
            'tryout_id' => $this->id,
            'name' => $this->name,
            'attempt' => $cereout
        ];
    }
}
