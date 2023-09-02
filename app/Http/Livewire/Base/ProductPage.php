<?php

namespace App\Http\Livewire\Base;

use App\Models\Umkm;
use App\Models\Product;
use Livewire\Component;
use App\Models\UserCart;
use App\Models\UserOrderItem;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;

class ProductPage extends Component
{
    // Route Binding Variable
    public $category_slug;

    // Model Variable
    public $categories;
    protected $products_query;
    public $products_result;

    // Binding Variable
    protected $category_filter;
    
    public $load_products = 12;
    private $load_products_increment = 12;
    public $all_loaded_state;
    
    protected $listeners = ['storeCart' => 'store_user_cart'];    
    
    public function mount($category_slug) {
        $this->category_slug = $category_slug;

        $category = ProductCategory::where('name_slug', '=', $this->category_slug)->get()->first();
        if ($category) {
            $this->category_filter = $category->id;
        } else {
            $this->category_filter = null;
        }

        $this->categories = ProductCategory::with([
            'product_active',
        ])->whereHas('product_active')->get();
        
        if ($this->category_filter) {
            $this->products_query = Product::with([
                'product_categories',
                'profile_image',
                'umkm',
            ])->where('status', '=', 'active')->whereHas('product_categories', function($query) {
                $query->where('category_id', '=', $this->category_filter);
            })->whereHas('umkm', function ($model) {
                return $model->where('status', '=', true);
            });
        } else {
            $this->products_query = Product::with([
                'product_categories',
                'profile_image',
                'umkm',
                'order_item',
            ])->where('status', '=', 'active')
            ->whereHas('umkm', function ($model) {
                return $model->where('status', '=', true);
            });
        }

        $this->products_result = $this->products_query->get();

        $this->set_loaded_state();
    }

    public function render()
    {
        $products = $this->products_result->take($this->load_products);
        return view('livewire.base.product-page', [
            'products' => $products,
        ])->layout('layouts.app');
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

    public function load_more() {
        $this->load_products += $this->load_products_increment;
        $this->set_loaded_state();
    }

    private function set_loaded_state() {
        if ($this->load_products >= $this->products_result->count()) {
            $this->all_loaded_state = true;
        } else {
            $this->all_loaded_state = false;
        }
    }

    public function store_user_cart($product_id) {
        if(Auth::check()) {
            
            $cart = UserCart::where([
                ['user_id', '=', Auth::user()->id],
                ['product_id', '=', $product_id],
            ])->get()->first();

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
            
            $msg = ['success' => 'Ditambahkan ke keranjang'];
            $this->dispatchBrowserEvent('display-message', $msg);
        }
    }

    public function count_filter_product_active_umkm($products) {
        $active_umkm_product_count = 0;
        
        foreach ($products as $product) {
            $check_umkm = Umkm::find($product->umkm_id);
            if ($check_umkm->status) {
                $active_umkm_product_count += 1;
            }
        }

        return $active_umkm_product_count;
    }

}
