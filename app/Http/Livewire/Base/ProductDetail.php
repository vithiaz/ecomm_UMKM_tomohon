<?php

namespace App\Http\Livewire\Base;

use App\Models\Product;
use Livewire\Component;
use App\Models\UserCart;
use App\Models\UserOrder;
use Illuminate\Support\Str;
use App\Models\UserOrderItem;
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
    public $success_transaction_count;

    public $delivery_selected_kec;
    public $delivery_price;

    public function updatedDeliverySelectedKec()
    {
        $kec_name = $this->delivery_selected_kec;
        $price_data = $this->product->product_delivery_prices->where('location', '=', $kec_name)->first();
        if ($price_data) {
            $this->delivery_price = $price_data->delivery_price;
        }
        else {
            $this->delivery_price = $this->product->base_delivery_price;
        }
        $this->dispatchBrowserEvent('calculatePrice');
    }

    public $city_dir = array(
        'Tomohon' => [
            'Taratara',
            'Taratara I',
            'Taratara II',
            'Taratara III',
            'Woloan I',
            'Woloan I Utara',
            'Woloan II',
            'Woloan III',
            'Kampung Jawa',
            'Lahendong',
            'Lansot',
            'Pangolombian',
            'Pinaras',
            'Tumatangtang',
            'Tumatangtang I',
            'Tondangow',
            'Uluindano',
            'Walian',
            'Walian I',
            'Walian II',
            'Kamasi',
            'Kamasi I',
            'Kolongan',
            'Kolongan I',
            'Matani I',
            'Matani II',
            'Matani III',
            'Talete I',
            'Talete II',
            'Kumelembuay',
            'Paslaten I',
            'Paslaten II',
            'Rurukan',
            'Rurukan I',
            'Kakaskasen',
            'Kakaskasen I',
            'Kakaskasen II',
            'Kakaskasen III',
            'Kayawu',
            'Kinilow',
            'Kinilow I',
            'Tinoor I',
            'Tinoor II',
            'Wailan',
        ],
    );


    protected $rules = [
        'qty_input' => 'required|numeric|min:1',
        'note_input' => 'nullable',
        'delivery_selected_kec' => 'required',
    ];

    protected $messages = [
        'qty_input.required' => 'Jumlah pembelian harus diisi',
        'qty_input.numeric' => 'Jumlah pembelian harus berupa angka',
        'qty_input.min' => 'Jumlah pembelian harus lebih dari 1',
    ];

    public function mount() {
        $product = Product::with([
            'profile_image',
            'umkm',
            'order_item',
            'product_delivery_prices',
        ])->where([
            ['id', '=', $this->product_id],
            ['name_slug', '=', $this->name_slug],
        ])->get();

        if ($product->count() < 1) {
            return abort(404);
        }

        $this->product = $product->first();

        $this->success_transaction_count = $this->count_success_transaction($this->product->order_item);

        $basePrice = (int)$this->product->price;
        $this->final_price = $basePrice - ($basePrice * ((float)$this->product->discount) / 100);

        $this->stock = $this->product->stock;
        $this->qty_input = 1;
        $this->note_input = '';
        
        $this->delivery_selected_kec = null;
        $this->delivery_price = $this->product->base_delivery_price;
    }

    public function render()
    {
        $other_product = Product::with([
            'profile_image',
            'umkm',
            'order_item',
        ])->whereNotIn('id', [$this->product->id])
        ->where('status', '=', 'active')
        ->whereHas('umkm', function ($model) {
            return $model->where('status', '=', true);
        })
        ->get()->take(8);
        return view('livewire.base.product-detail', ['other_product' => $other_product])->layout('layouts.app');
    }

    public function count_success_transaction($order_items) {
        $success_transaction_count = 0;

        if ($order_items) {
            foreach($order_items as $order) {
                $orderItem = UserOrderItem::withCount('order_success')->find($order->id);
                if ($orderItem->order_success_count > 0) {
                    $success_transaction_count += $orderItem->qty;
                }
            }
        }
        return $success_transaction_count;
    }

    
    public function store_user_cart($product_id, $in_detail_page = true) {
        if (!Auth::check()) {
            $msg = ['info' => 'Silahkan Login terlebih dahulu'];
            $this->dispatchBrowserEvent('display-message', $msg);
            return; 
        }
        
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
        if (!Auth::check()) {
            $msg = ['info' => 'Silahkan Login terlebih dahulu'];
            $this->dispatchBrowserEvent('display-message', $msg);
            return; 
        }

        $this->store_user_cart($this->product->id, true);
        return redirect()->route('cart-page');
    }

}
