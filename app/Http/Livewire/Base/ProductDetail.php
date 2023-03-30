<?php

namespace App\Http\Livewire\Base;

use App\Models\Product;
use Livewire\Component;

class ProductDetail extends Component
{
    // Route Binding Variable
    public $product_id;
    public $name_slug;

    // Binding Variable
    public $final_price;

    // Model Variable
    public $product;

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
    }

    public function render()
    {
        return view('livewire.base.product-detail')->layout('layouts.app');
    }
}
