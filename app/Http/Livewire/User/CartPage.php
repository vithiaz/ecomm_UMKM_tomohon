<?php

namespace App\Http\Livewire\User;

use App\Models\Umkm;
use Livewire\Component;
use App\Models\UserCart;
use Illuminate\Support\Facades\Auth;

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

        $this->Umkm = Umkm::whereIn('id', array_keys($this->Cart))->get();

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
        dd($checked_cart);
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
   

}

// Checkpoint
// Fix checkout button per UMKM table