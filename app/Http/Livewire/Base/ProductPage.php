<?php

namespace App\Http\Livewire\Base;

use App\Models\Product;
use Livewire\Component;
use App\Models\ProductCategory;

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
                'product_images',
                'umkm',
            ])->where('status', '=', 'active')->whereHas('product_categories', function($query) {
                $query->where('category_id', '=', $this->category_filter);
            });
        } else {
            $this->products_query = Product::with([
                'product_categories',
                'product_images',
                'umkm',
            ])->where('status', '=', 'active');
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

}
