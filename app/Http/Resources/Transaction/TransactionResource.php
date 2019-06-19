<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Membership;
use App\Models\NominalTopUp;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $membership_name = null;
        $price = null;
        if($this->type==1){
            if($this->membership_id!=null){
                $membership = Membership::where('id','=',$this->membership_id)->first();
                $membership_name = $membership->name;
            }
        }elseif($this->type==2){
            if($this->membership_id!=null){
                $nominal = NominalTopUp::where('id','=',$this->membership_id)->first();
                $price = $nominal->harga;
                $membership_name = $nominal->nominal;
            }
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nominal' => $price,
            'harga' => $this->nominal,
            'payment_method' => $this->payment_method,
            'snap_token' => $this->snap_token,
            'status' => $this->status,
            'membership_id' => $this->membership_id,
            'membership_name' => $membership_name,
            'type' => $this->type,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans()
        ];
    }
}
