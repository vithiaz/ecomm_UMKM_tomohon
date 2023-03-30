<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class ProductReview extends Component
{
    // Route Binding Variable
    public $product_id;
    public $name_slug;

    // Model Variable
    public $product;

    // Binding Variable
    public $final_price;

    public function mount($product_id, $name_slug) {
        $this->product_id = $product_id;
        $this->name_slug = $name_slug;

        $this->product = Product::where([
            ['id', '=', $this->product_id],
            ['name_slug', '=', $this->name_slug],
        ])->get()->first();

        if ($this->product == null) {
            return abort(404);
        }

        $basePrice = (int)$this->product->price;
        $this->final_price = $basePrice - ($basePrice * ((float)$this->product->discount) / 100);
    }


    public function render()
    {
        return view('livewire.admin.product-review')->layout('layouts.admin_app');
    }

    public function set_status($status) {
        $this->product->status = $status;
        $this->product->save();
        return redirect()->route('admin.products', ['status' => 'active'])->with('message', 'Produk di nonaktfikan');
    }

    public function redirect_back() {
        return redirect()->route('admin.products', ['status' => 'active']);
    }

}
