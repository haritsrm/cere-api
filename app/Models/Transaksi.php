<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'user_id', 'nominal', 'payment_method', 'status', 'snap_token', 'membership_id','type'
    ];

    public function setPending($type)
    {
        $this->attributes['status'] = 'pending';
        $this->attributes['payment_method'] = $type;
        self::save();
    }
 
    public function setSuccess($type)
    {
        $this->attributes['status'] = 'success';
        $this->attributes['payment_method'] = $type;
        self::save();
    }

    public function setFailed($type)
    {
        $this->attributes['status'] = 'failed';
        $this->attributes['payment_method'] = $type;
        self::save();
    }
 
    public function setExpired($type)
    {
        $this->attributes['status'] = 'expired';
        $this->attributes['payment_method'] = $type;
        self::save();
    }
}
