<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\UserCart;
use App\Models\UserOrder;
use Illuminate\Support\Str;
use App\Models\UserOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutPage extends Component
{
    // Model Variable
    public $userCart;
    public $umkm;

    // Binding Variable
    public $delivery_address;
    public $set_delivery_address;
    public $gross_amount;

    protected $rules = [
        'delivery_address' => 'required|string',
    ];
    
    public function updatedSetDeliveryAddress() {
        if ($this->set_delivery_address) {
            $this->delivery_address = Auth::user()->address;
        } else {
            $this->delivery_address = '';
        }
        $this->dispatchBrowserEvent('delivery-address-changed');
    }

    
    public function mount() {
        $data_id = session('data');
        
        if ($data_id == null) {
            return redirect()->route('cart-page');
        }

        $this->userCart = UserCart::with([
                            'umkm',
                            'product',
                        ])->whereIn('id' , $data_id)->get();
        
        $this->umkm = $this->userCart->first()->umkm;
        
        $user_address = Auth::user()->address;
        if ($user_address) {
            $this->delivery_address = Auth::user()->address;
            $this->set_delivery_address = true;
        } else {
            $this->delivery_address = '';
            $this->set_delivery_address = false;
        }

        $this->gross_amount = $this->calc_gross_amount();
    }

    public function render()
    {
        return view('livewire.user.checkout-page')->layout('layouts.user_settings_app');
    }

    public function get_final_price($basePrice, $discount) {
        if ($discount > 0) {
            $Amount = (int)$basePrice;
            $CalculatedAmount = $Amount - ($Amount * ((float)$discount) / 100);
        } else {
            $CalculatedAmount = (int)$basePrice;
        }
        return $CalculatedAmount;
    }

    public function calculate_amount($final_price, $qty) {
        return (int)$final_price * (int)$qty;
    }

    public function init_payment() {
        $this->validate();

        $server_key = config('midtrans.server_key');
        $request_url = config('midtrans.snap_request_url');

        // Begin Transaction
        try {
            DB::beginTransaction();
            // Handle UserOrder
            $userOrder = new UserOrder;
            $orderId = Str::uuid()->toString();
            $userOrder->id = $orderId;
            $userOrder->buyer_id = Auth::user()->id;
            $userOrder->umkm_id = $this->umkm->id;
            $userOrder->order_address = $this->delivery_address;
            $userOrder->payment_status = 'pending';
            $userOrder->order_status = 'pending';
            $userOrder->payment_amount = $this->gross_amount;
            $userOrder->save();
    
            // Handle UserOrderItem
            $item_details = [];
            foreach($this->userCart as $cart) {
                $userOrderItem = new UserOrderItem;
                $itemOrderId = Str::uuid()->toString();
                $userOrderItem->id = $itemOrderId;
                $userOrderItem->order_id = $orderId;
                $userOrderItem->product_id = $cart->product->id;
                $userOrderItem->qty = $cart->qty;
    
                $basePrice = (int)$cart->product->price;
                $itemPrice = $basePrice - ($basePrice * ((float)$cart->product->discount) / 100);
    
                $userOrderItem->amount = $itemPrice;
                $userOrderItem->delivery_status = 'pending';
                $userOrderItem->message = $cart->message;
                $userOrderItem->save();
                
                $item_detail = array(
                    'id' => $itemOrderId,
                    'price' => (int)$itemPrice,
                    'quantity' => $cart->qty,
                    'name' => $cart->product->name_slug
                );
                array_push($item_details, $item_detail);
            }
    
            // Set the request data
            $request_data = array(
                'transaction_details' => array(
                    'order_id' => $orderId,
                    'gross_amount' => $this->gross_amount,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->first_name,
                    'last_name' => Auth::user()->last_name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone_number,
                    'delivery_address' => $this->delivery_address,
                ),
            );
            $request_data['item_details'] = $item_details;
            
            // Set the request headers
            $request_headers = array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($server_key . ':')
            );

            // Create the HTTP client and request object
            $client = curl_init($request_url);
            curl_setopt($client, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($client, CURLOPT_POSTFIELDS, json_encode($request_data));
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($client, CURLOPT_HTTPHEADER, $request_headers);
    
            // Send the HTTP request and get the response
            $response = curl_exec($client);
            curl_close($client);
            
            if ($response == null) {
                $msg = ['error' => 'Connection error'];
                $this->dispatchBrowserEvent('display-message',  $msg);
            } else {
                $response_json = json_decode($response);
                $token = $response_json->token;
                $redirect_url = $response_json->redirect_url;
                DB::commit();
                
                // Move UserCart to UserOrder Table
                if ($token) {
                    foreach ($this->userCart as $cart) {
                        $cart->delete();
                    }

                    $userOrder->payment_token = $token;
                    $userOrder->save();
                }

                $this->dispatchBrowserEvent('snap-popup', ['token' => $token]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            $msg = ['error' => $e->getMessage()];
            $this->dispatchBrowserEvent('display-message',  json_decode($msg)->error_messages);
        }

    }

    private function calc_gross_amount() {
        $gross_amount = 0;
        foreach ($this->userCart as $cart) {
            $basePrice = (int)$cart->product->price;
            $finalPrice = $basePrice - ($basePrice * ((float)$cart->product->discount) / 100);
            $itemPrice = $finalPrice * (int)$cart->qty;
            $gross_amount += $itemPrice;
        }
        return $gross_amount;
    }

}
