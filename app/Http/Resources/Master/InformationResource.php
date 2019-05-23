<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Information;

class InformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $announcements = Information::where('category', 'announcements')->get();
        $sliders = Information::where('category', 'sliders')->get();
        return [
            'announcemets' => $announcements,
            'sliders' => $sliders
        ];
    }
}
