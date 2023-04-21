<?php

namespace App\Http\Livewire\Component;

use Livewire\Component;
use App\Models\UserCart;
use App\Models\UserOrderItem;
use Illuminate\Support\Facades\Auth;

class ProductCard extends Component
{
    // Component Binding Variable
    public $product;

    // Binding Variable
    public $sales_qty;

    
    public function mount($product) {
        $this->product = $product;

        $this->sales_qty = $this->get_product_sales_qty($this->product->id);
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

    // Handle product selling qty
    private function get_product_sales_qty($product_id) {
        $sales_qty = 0;
        $userOrderItem = UserOrderItem::where('product_id', '=', $this->product->id)->with('order_belongs')->get();
        foreach($userOrderItem as $order_item) {
            $user_order_belongs = $order_item->order_belongs()->withCount('success_transaction')->get();
            foreach ($user_order_belongs as $item_to_user_orders) {
                $transaction_count = $item_to_user_orders->success_transaction_count;
                if ($transaction_count > 0) {
                    $sales_qty += $order_item['qty'];
                }
            }
        }
        return $sales_qty;
    }

}
