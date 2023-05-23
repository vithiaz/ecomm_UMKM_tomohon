<?php

namespace App\Http\Livewire\Base;

use App\Models\Product;
use Livewire\Component;
use App\Models\UserCart;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;

class SearchPage extends Component
{
    // Model Variable
    public $categories;
    protected $products_query;
    public $products_result;

    // Binding Variable
    protected $category_filter;
    public $search_key;

    // public $category_slug;
    public $load_products = 12;
    private $load_products_increment = 12;
    public $all_loaded_state;
    
    protected $listeners = ['storeCart' => 'store_user_cart'];  

    public function mount($search_key) {
        // $this->category_slug = '0';

        // $category = ProductCategory::where('name_slug', '=', $this->category_slug)->get()->first();
        // if ($category) {
        //     $this->category_filter = $category->id;
        // } else {
        //     $this->category_filter = null;
        // }

        $this->search_key = $search_key;

        $this->categories = ProductCategory::with([
            'product_active',
        ])->whereHas('product_active')->get();
        
        $this->products_query = Product::with([
            'product_categories',
            'profile_image',
            'umkm',
            'categories',
        ])->where([
            ['status', '=', 'active'],
            ['name_slug', 'like', '%'.$this->search_key.'%'],
        ])->orWherehas('umkm', function($q) {
            return $q->where('name', 'like', '%'.$this->search_key.'%');
        })->orWherehas('categories', function($q) {
            return $q->where('name', 'like', '%'.$this->search_key.'%');
        });

        $this->products_result = $this->products_query->get();

        $this->set_loaded_state();
    }

    public function render()
    {
        $products = $this->products_result->take($this->load_products);
        return view('livewire.base.search-page', [
            'products' => $products,
        ])->layout('layouts.app');
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

}
