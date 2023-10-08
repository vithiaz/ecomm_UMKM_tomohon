<?php

namespace App\Http\Livewire\User;

use App\Models\Umkm;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Livewire\WithFileUploads;
use App\Models\ProductCategory;
use App\Models\ProductCategories;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class AddProduct extends Component
{
    use WithFileUploads;

    // Route Binding
    public $umkm;

    // Model Variable
    public $categories;

    // Binding Variable
    public $name;
    public $stock;
    public $category;
    public $selected_categories;
    public $discount;
    public $price;
    public $description;
    public $image;

    protected $rules = [
        'name' => 'required|string',
        'stock' => 'required|numeric|min:0',
        'discount' => 'required|numeric|min:0|max:100',
        'price'=> 'required|numeric',
        'description' => 'required|string',
    ];

    public function updatedCategory() {
        $category_data = null;

        foreach ($this->categories as $cat) {
            if ($cat['id'] == $this->category) {
                $category_data = [
                    'id' => $cat['id'],
                    'name' => $cat['name'],
                ];
            }
        }

        if ($category_data) {
            if (!in_array($category_data, $this->selected_categories)) {
                array_push($this->selected_categories, $category_data);
            }
        }
    }

    public function mount(Umkm $umkm) {
        $this->umkm = $umkm;
        if ($this->umkm == null) {
            return abort(404);
        }
        if($this->umkm->user_id != Auth::user()->id) {
            return abort(403);
        }

        $this->categories = ProductCategory::get()->all();

        $this->name = null;
        $this->stock = null;
        $this->discount = null;
        $this->price = null;
        $this->description = null;
        $this->image = null;
        $this->category = null;
        $this->selected_categories = [];
    }

    public function render()
    {
        // $categories = ProductCategory::get()->all();

        return view('livewire.user.add-product')->layout('layouts.user_settings_app');
    }

    public function empty_image() {
        $this->image = null;
    }

    private function find_id_in_cat_array($id, $cat_array) {
        foreach($cat_array as $cat) {
            if($cat['id'] == $id) {
                return $cat;
            }
        }
        return null;
    }

    public function remove_selected_category($cat_id) {
        $cat_data =  $this->find_id_in_cat_array($cat_id, $this->selected_categories);

        if ($cat_data) {
            $key = array_search($cat_data, $this->selected_categories);
            unset($this->selected_categories[$key]);
        }
    }

    public function store_product() {
        $this->validate();
        
        $product = new Product;

        $generator_rules = [
            'table' => 'products',
            'length' => '9',
            'prefix' => date('ymd'),
        ];
        $id = IdGenerator::generate($generator_rules);

        $product->id = $id;
        $product->name = ucwords($this->name);
        $product->name_slug = Str::slug($this->name);
        $product->stock = $this->stock;
        $product->discount = $this->discount;
        $product->price = $this->price;
        $product->description = $this->description;
        $product->status = 'active';
        $product->umkm_id = $this->umkm->id;
        $product->save();

        if ($this->image) {
            $product_image = new ProductImage;
            $product_image->image = $this->image->store('product_images');
            $product_image->product_id = $product->id;
            $product_image->save();
        }

        if ($this->selected_categories) {
            foreach($this->selected_categories as $cat) {
                $product_category = new ProductCategories;
                $product_category->category_id = $cat['id'];
                $product_category->product_id = $product->id;
                $product_category->save();
            }
        }

        return redirect()->route('umkm.profile')->with('message', 'Produk Ditambahkan');
    }


}
