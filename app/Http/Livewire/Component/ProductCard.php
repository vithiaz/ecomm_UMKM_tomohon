<?php

namespace App\Http\Livewire\Component;

use Livewire\Component;
use App\Models\UserCart;
use Illuminate\Support\Facades\Auth;

class ProductCard extends Component
{
    // Component Binding Variable
    public $product;
    
    public function mount($product) {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.component.product-card');
    }

    public function get_final_price($basePrice, $discount) {
        if ($discount > 0) {
            $Amount = (int)$basePrice;
            $CalculatedAmount = $Amount - ($Amount * ((float)$discount) / 100);
        } else {
            $CalculatedAmount = (int)$basePrice;
        }
        return format_rupiah($CalculatedAmount);
    }

    public function add_to_cart() {
        if(Auth::check()) {
            
            $cart = UserCart::where([
                ['user_id', '=', Auth::user()->id],
                ['product_id', '=', $this->product->id],
            ])->get()->first();

            if ($cart != null) {
                $cart->qty += 1;
                $cart->save();
            }
            else {
                $newCart = new UserCart;
                $newCart->user_id = Auth::user()->id;
                $newCart->product_id = $this->product->id;
                $newCart->qty = 1;
                $newCart->save();
            }            
            
            $msg = ['success' => 'Ditambahkan ke keranjang'];
            $this->dispatchBrowserEvent('display-message', $msg);
        }
    }

}
