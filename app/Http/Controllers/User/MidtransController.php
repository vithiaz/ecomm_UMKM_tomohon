<?php

namespace App\Http\Controllers\User;

use App\Models\UserOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MidtransController extends Controller
{
    public function payment_callback(Request $request) {
        $serverkey = config('midtrans.server_key');
        $hashed = hash('sha512',
            $request->order_id
            .$request->status_code
            .$request->gross_amount
            .$serverkey
        );
        if ($hashed == $request->signature_key) {
            $order = UserOrder::find($request->order_id);
            $order->update(['payment_status' => $request->transaction_status]);
            if ($request->transaction_status == 'settlement') {   
                $order->update(['order_status' => 'progress']);
            }
        }
    }
}
