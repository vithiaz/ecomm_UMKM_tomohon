<?php

namespace App\Http\Livewire\User;

use App\Models\Umkm;
use Livewire\Component;
use App\Models\UserCart;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CartPage extends Component
{
    // Model Variable
    public $Cart;
    public $Umkm;

    // Binding Variable
    public $checked_cart;

    public $cart_modify_state;
   
    public function mount() {
        $this->Cart = UserCart::with([
            'user',
            'product',
            'umkm',
        ])->where('user_id', '=', Auth::user()->id)->get()->groupBy('umkm.id')->toArray();

        $cartKeys = [];
        foreach (array_keys($this->Cart) as $key) {
            array_push($cartKeys, strval($key));
        }

        $this->Umkm = Umkm::whereIn('id', $cartKeys)->get();
        // $this->Umkm = Umkm::whereIn('id', array_keys($this->Cart))->get();

        $this->temp_var = '';

        $this->checked_cart = [];
        $this->cart_modify_state = [];
        
        foreach($this->Umkm as $umkm_cart) {
            foreach($this->Cart[$umkm_cart->id] as $cartGroup) {
                array_push( $this->checked_cart,  $umkm_cart->id.'-'.$cartGroup['id'] );
            }
        }
    }

    public function render()
    {
        return view('livewire.user.cart-page')->layout('layouts.user_settings_app');
    }

    public function get_product_image($product_id) {
        $Image = ProductImage::where('product_id', '=', $product_id)->get()->first();
        if ($Image) {
            return $Image->image;
        }
        return null;
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

    public function item_checkout($id) {                // $id => umkm.id
        $checked_cart = [];
        foreach ($this->checked_cart as $cart) {
            $umkm_id = explode('-', $cart)[0];
            $cart_id = explode('-', $cart)[1];

            if ($id == $umkm_id) {
                array_push($checked_cart, $cart_id);
            }
        }
        
        $this->save_edited_qty($id);

        if ($checked_cart) {
            return redirect()->route('checkout')->with('data', $checked_cart);
        }
    }

    private function save_edited_qty($umkm_id) {
        foreach ($this->cart_modify_state as $mod) {
            if ($mod[2] == $umkm_id) {
                $cart = UserCart::where('id', '=', $mod[0])->first();
                if ($cart) {
                    $cart->qty = $mod[1];
                    $cart->save();
                }
            }
        }
    }

    public function collect_modify_cart($cart_id, $qty, $umkm_id) {
        $modify_cart = [$cart_id, $qty, $umkm_id];
        
        if ( !in_array($cart_id, array_column($this->cart_modify_state, 0)) ) {
            array_push($this->cart_modify_state, $modify_cart);
        } else {
            $key = array_search($cart_id, array_column($this->cart_modify_state, 0));
            $this->cart_modify_state[$key][1] = $qty;
        }

        $this->dispatchBrowserEvent('refreshScript');
    }

    public function delete_user_cart($cart_id) {
        $cart_to_delete = UserCart::find($cart_id);
        if ($cart_to_delete) {
            $cart_to_delete->delete();
            return redirect(request()->header('Referer'))->with('message', 'Produk dihapus dari keranjang');
        }
    }
   





    
    // Note and Draft Midtrans Function
    // 
    //     
    private function transfer_payment() {
        $server_key = config('midtrans.server_key');
        $request_url = 'https://app.sandbox.midtrans.com/iris/api/v1/beneficiaries';
    
        // Set the request data
        $request_data = array(
            "name" => "John Doe",
            "account" => "00010000",
            "bank" => "bni",
            "alias_name" => "johnbca",
            "email" => "beneficiary@example.com"
        );
    
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
    
        dd($response);
    }
    
    // private function transfer_payment() {
    //     $server_key = config('midtrans.server_key');
    //     $request_url = 'https://api.sandbox.midtrans.com/v2/charge';
    
    //     // Set the request data
    //     $request_data = array(
    //         "payment_type" => "bank_transfer",
    //         "transaction_details" => array(
    //             'order_id' => "order-106",
    //             'gross_amount' => 42000
    //         ),
    //         "bank_transfer" => array(
    //             "bank" => "bni"
    //         )
    //     );
    
    //     // Set the request headers
    //     $request_headers = array(
    //         'Content-Type: application/json',
    //         'Accept: application/json',
    //         'Authorization: Basic ' . base64_encode($server_key . ':')
    //     );
    
    //     // Create the HTTP client and request object
    //     $client = curl_init($request_url);
    //     curl_setopt($client, CURLOPT_CUSTOMREQUEST, 'POST');
    //     curl_setopt($client, CURLOPT_POSTFIELDS, json_encode($request_data));
    //     curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($client, CURLOPT_HTTPHEADER, $request_headers);
    
    //     // Send the HTTP request and get the response
    //     $response = curl_exec($client);
    //     curl_close($client);
    
    //     dd($response);
    // }
    
    private function request_get_banks() {
        // Set your server key and request endpoint URL
        $server_key = config('midtrans.server_key');
        $request_url = 'https://api.sandbox.midtrans.com/v2/banks';
    
        // Set the request headers
        $request_headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($server_key . ':')
        );
    
        // Create the HTTP client and request object
        $client = curl_init($request_url);
        curl_setopt($client, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_HTTPHEADER, $request_headers);
    
        // Send the HTTP request and get the response
        $response = curl_exec($client);
        curl_close($client);
    
        // Process the response
        if ($response) {
            $response_obj = json_decode($response);
            dd($response_obj);
        } else {
            dd($response);
        }
    
    }
    
    
    public function createPayout()
    {
        // Set your server key and request endpoint URL
        $server_key = config('midtrans.server_key');
        $request_url = 'https://api.midtrans.com/v1/payouts';
    
        // Set the request data
        $request_data = array(
            'reference_no' => '10110111',
            'amount' => 100000,
            'bank' => 'bni',
            'bank_account' => array(
                'bank_account_name' => 'johndoebni',
                'bank_account_number' => '11220032'
            ),
            'email_to' => 'janedoe@mail.com',
            'sender_name' => 'janedoe',
            'description' => 'payout-1011'
        );
    
        // Set the request headers
        $request_headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($server_key . ':')
        );
    
        // Create the HTTP client and request object
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $request_url, [
            'headers' => $request_headers,
            'json' => $request_data
        ]);
    
        dd($response);
    
        // Process the response
        if ($response->getStatusCode() == 200) {
            $response_obj = json_decode($response->getBody());
            if (isset($response_obj->id)) {
                // Payout created successfully
                return 'Payout created with ID: ' . $response_obj->id;
            } else {
                // Handle the error
                return 'Error: ' . $response_obj->status_message;
            }
        } else {
            // Handle the error
            return 'Error: Failed to create payout.';
        }
    }
}
