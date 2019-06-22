<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Veritrans\Veritrans;
use Veritrans_Snap;
use Veritrans_Notification;
use App\Models\Transaksi;
use App\User;
use Carbon\Carbon;
use App\Models\Membership;
use App\Models\NominalTopUp;
use Veritrans_Config;
use App\Http\Resources\Membership\MembershipResource;
use App\Http\Resources\Transaction\TransactionResource;

class PaymentController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
 
        // Set midtrans configuration
        Veritrans_Config::$serverKey = config('services.midtrans.serverKey');
        Veritrans_Config::$isProduction = config('services.midtrans.isProduction');
        Veritrans_Config::$isSanitized = config('services.midtrans.isSanitized');
        Veritrans_Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function submitPayment(Request $request)
    {
        \DB::transaction(function(){
            // Save transaksi ke database
            if($this->request->type==1){
                $membership = Membership::where('id',$this->request->membership_id)->first();
                if ($this->request->coupon_code == $membership->coupon_code && $membership->status == 1) {
                    $nominal = $membership->price-$membership->coupon_price;
                    $isCoupon = true;
                }else{
                    $nominal = $this->request->nominal;
                    $isCoupon = false;    
                }
            }else{
                $nominal = $this->request->nominal;
                $isCoupon = false;
            }

            $payment = Transaksi::create([
                'user_id' => $this->request->user_id,
                'nominal' => $nominal,
                'membership_id' => $this->request->membership_id,
                'type' => $this->request->type
            ]);

            $user = User::where('id','=',$payment->user_id)->first();
 			
            // Buat transaksi ke midtrans kemudian save snap tokennya.
            $payload = [
                'transaction_details' => [
                    'order_id'      => $payment->id,
                    'gross_amount'  => $payment->nominal,
                    'name'  => $payment->membership_id
                ],
                'customer_details' => [
                    'first_name'    => $user->name,
                    'email'         => $user->email,
                    'phone'         => $user->phone,
                    // 'address'       => '',
                ]
            ];
            $snapToken = Veritrans_Snap::getSnapToken($payload);
            $payment->snap_token = $snapToken;
            $payment->save();
 
            // Beri response snap token
            $this->response['snap_token'] = $snapToken;
            $this->response['isCoupon'] = $isCoupon;
        });
 
        return response()->json($this->response);
    }

        public function notificationHandler(Request $request)
    {
        $notif = new Veritrans_Notification();
        \DB::transaction(function() use($notif) {
 
          $transaction = $notif->transaction_status;
          $type = $notif->payment_type;
          $orderId = $notif->order_id;
          $fraud = $notif->fraud_status;
          $transactions = Transaksi::findOrFail($orderId);
 
          if ($transaction == 'capture') {
 
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
 
              if($fraud == 'challenge') {
                // TODO set payment status in merchant's database to 'Challenge by FDS'
                // TODO merchant should decide whether this transaction is authorized or not in MAP
                // $donation->addUpdate("Transaction order_id: " . $orderId ." is challenged by FDS");
                // $transactions->setPending($type);
                $data = Transaksi::where('id',$orderId)->first();
                $data->status="2";
                $data->payment_method=$type;
                $data->save();
              } else {
                // TODO set payment status in merchant's database to 'Success'
                // $donation->addUpdate("Transaction order_id: " . $orderId ." successfully captured using " . $type);
                // $transactions->setSuccess($type);
                $data = Transaksi::where('id',$orderId)->first();
                $data->status="1";
                $data->payment_method=$type;
                $data->save();
                $price = NominalTopup::where('id',$data->membership_id)->first();
                if($data->type==1){
                    $user=User::where('id','=',$transactions->user_id)->first();
                    $user->membership=true;
                    $user->save();    
                }else{
                    $today =  Carbon::now();
                    $user=User::where('id','=',$transactions->user_id)->first();
                    $user->balance+=$price->nominal;
                    $user->last_transaction+=$today;
                    $user->save();
                }
              }
 
            }
 
          } elseif ($transaction == 'settlement') {
 
            // TODO set payment status in merchant's database to 'Settlement'
            // $donation->addUpdate("Transaction order_id: " . $orderId ." successfully transfered using " . $type);
            // $transactions->setSuccess($type);

            $data = Transaksi::where('id',$orderId)->first();
            $data->status="1";
            $data->payment_method=$type;
            $data->save();
            $price = NominalTopup::where('id',$data->membership_id)->first();
            if($data->type==1){
                $user=User::where('id','=',$transactions->user_id)->first();
                $user->membership=true;
                $user->save();    
            }else{
                $user=User::where('id','=',$transactions->user_id)->first();
                $user->balance+=$price->nominal;

                $user->save();
            }
 
          } elseif($transaction == 'pending'){
 
            // TODO set payment status in merchant's database to 'Pending'
            // $donation->addUpdate("Waiting customer to finish transaction order_id: " . $orderId . " using " . $type);
            // $transactions->setPending($type);
            $data = Transaksi::where('id',$orderId)->first();
            $data->status="2";
            $data->payment_method=$type;
            $data->save();
 
          } elseif ($transaction == 'deny') {
 
            // TODO set payment status in merchant's database to 'Failed'
            // $donation->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is Failed.");
            // $transactions->setFailed($type);
            $data = Transaksi::where('id',$orderId)->first();
            $data->status="3";
            $data->payment_method=$type;
            $data->save();
 
          } elseif ($transaction == 'expire') {
 
            // TODO set payment status in merchant's database to 'expire'
            // $donation->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is expired.");
            // $transactions->setExpired($type);
            $data = Transaksi::where('id',$orderId)->first();
            $data->status="4";
            $data->payment_method=$type;
            $data->save();
 
          } elseif ($transaction == 'cancel') {
 
            // TODO set payment status in merchant's database to 'Failed'
            // $donation->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is canceled.");
            // $transactions->setFailed($type);
            $data = Transaksi::where('id',$orderId)->first();
            $data->status="3";
            $data->payment_method=$type;
            $data->save();
 
          }
 
        });
 
        return;
    }

    public function getTransactionByUser($id){
        $data = Transaksi::where('user_id','=',$id)->get();
                // join('membership','membership.id','=','transactions.membership_id')
                // ->select('transactions.*','membership.name as membership_name')
                

        return TransactionResource::collection($data);
    }

    public function getMembership(){
        $today =  Carbon::now()->todatestring();
        $data = Membership::where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->get();

        return MembershipResource::collection($data);
    }

    public function UpdatePayment($id)
    {
        $payment = Transaksi::where('id','=',$id)->first();
        $user = User::where('id','=',$payment->user_id)->first();
        // Buat transaksi ke midtrans kemudian save snap tokennya.
        $payload = [
            'transaction_details' => [
                'order_id'      => $payment->id,
                'gross_amount'  => $payment->nominal,
                'name'  => $payment->membership_id
            ],
            'customer_details' => [
                'first_name'    => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                // 'address'       => '',
            ]
        ];
        $snapToken = Veritrans_Snap::getSnapToken($payload);
        $payment->snap_token = $snapToken;
        $payment->save();

        // Beri response snap token
        $this->response['snap_token'] = $snapToken;
 
        return response()->json($this->response);
    }
}
