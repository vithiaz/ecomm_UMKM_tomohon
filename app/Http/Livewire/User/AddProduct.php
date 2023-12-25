<?php

namespace App\Http\Livewire\User;

use App\Models\Umkm;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\DeliveryPrice;
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
    public $base_delivery_price;
    public $selected_kec;
    public $kec_delivery_price;
    public $kec_delivery_detail;

    protected $rules = [
        'name' => 'required|string',
        'stock' => 'required|numeric|min:0',
        'discount' => 'required|numeric|min:0|max:100',
        'price' => 'required|numeric',
        'description' => 'required|string',
        'base_delivery_price' => 'required|numeric',
    ];

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
        
        $this->base_delivery_price = null;
        $this->kec_delivery_price = null;
        $this->selected_kec = null;
        $this->kec_delivery_detail = [];
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
        $product->base_delivery_price = $this->base_delivery_price;
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

        if ($this->kec_delivery_detail) {
            foreach($this->kec_delivery_detail as $delivery_detail) {
                $delivery_price = new DeliveryPrice;
                $delivery_price->product_id = $product->id;
                $delivery_price->location = $delivery_detail['kecamatan'];
                $delivery_price->delivery_price = $delivery_detail['price'];
                $delivery_price->save();
            }
        }

        return redirect()->route('umkm.profile')->with('message', 'Produk Ditambahkan');
    }

    public function add_delivery_detail() {
        if (
            $this->kec_delivery_price != false &&
            $this->selected_kec != false
        ) {
            $kecamatanExists = false;
            foreach ($this->kec_delivery_detail as $idx=>$item) {
                if ($item['kecamatan'] == $this->selected_kec) {
                    $this->kec_delivery_detail[$idx]['price'] = $this->kec_delivery_price;
                    // $item['price'] = $this->kec_delivery_price;
                    $kecamatanExists = true;
                    break;
                }
            }
            if (!$kecamatanExists) {
                array_push($this->kec_delivery_detail, [
                    "price" => $this->kec_delivery_price,
                    "kecamatan" => $this->selected_kec
                ]);
            }
        }
    }
    
    public function delete_delivery_detail($idx) {
        unset($this->kec_delivery_detail[$idx]);
    }


}
