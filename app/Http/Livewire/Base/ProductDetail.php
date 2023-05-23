<?php

namespace App\Http\Livewire\Base;

use App\Models\Product;
use Livewire\Component;
use App\Models\UserCart;
use App\Models\UserOrder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductDetail extends Component
{
    // Route Binding Variable
    public $product_id;
    public $name_slug;

    // Model Variable
    public $product;
    
    // Binding Variable
    public $final_price;
    public $stock;
    public $qty_input;
    public $note_input;


    protected $rules = [
        'qty_input' => 'required|numeric',
        'note_input' => 'nullable',
    ];

    public function mount() {
        $product = Product::with([
            'profile_image',
            'umkm',
        ])->where([
            ['id', '=', $this->product_id],
            ['name_slug', '=', $this->name_slug],
        ])->get();

        if ($product->count() < 1) {
            return abort(404);
        }

        $this->product = $product->first();

        $basePrice = (int)$this->product->price;
        $this->final_price = $basePrice - ($basePrice * ((float)$this->product->discount) / 100);

        $this->stock = $this->product->stock;
        $this->qty_input = '';
        $this->note_input = '';
    }

    public function render()
    {
        $other_product = Product::with([
            'profile_image',
            'umkm',
        ])->whereNotIn('id', [$this->product->id])->get()->take(8);
        return view('livewire.base.product-detail', ['other_product' => $other_product])->layout('layouts.app');
    }

    public function store_user_cart($product_id, $in_detail_page = true) {
        $cart = UserCart::where([
            ['user_id', '=', Auth::user()->id],
            ['product_id', '=', $product_id],
        ])->get()->first();

        if (!$in_detail_page) {
            if ($cart != null) {
                $cart->qty += 1;
                $cart->save();
            }
            else {
                $newCart = new UserCart;
                $newCart->user_id = Auth::user()->id;
                $newCart->product_id = $product_id;
                $newCart->qty = 1;
                $newCart->save();
            }
        } else {
            $this->validate();
            $newCart = new UserCart;
            $newCart->user_id = Auth::user()->id;
            $newCart->product_id = $product_id;
            $newCart->qty = $this->qty_input;
            $newCart->message = $this->note_input;
            $newCart->save();

            $this->qty_input = '';
            $this->note_input = '';
        }

        $msg = ['success' => 'Ditambahkan ke keranjang'];
        $this->dispatchBrowserEvent('display-message', $msg);
    }


    public function direct_buy() {
        $this->store_user_cart($this->product->id, true);
        return redirect()->route('cart-page');
    }

}
