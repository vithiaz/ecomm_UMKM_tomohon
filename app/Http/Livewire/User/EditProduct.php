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

class EditProduct extends Component
{
    use WithFileUploads;

    // Route Binding Variable
    public $umkm;
    public $product;

    // Model Variable
    public $categories;

    // Binding Variable
    public $product_id;
    public $status;
    public $name;
    public $stock;
    public $category;
    public $selected_categories;
    public $saved_categories;
    public $saved_categories_to_delete;
    public $discount;
    public $price;
    public $description;
    public $image;
    public $upload_image;

    public $image_delete_state;
    public $displayImage;
    public $disabled_status;

    public $delivery_prices;
    public $base_delivery_price;
    public $selected_kec;
    public $kec_delivery_price;
    public $kec_delivery_detail;

    protected $rules = [
        'status' => 'nullable',
        'name' => 'required|string',
        'stock' => 'required|numeric|min:0',
        'discount' => 'required|numeric|min:0|max:100',
        'price'=> 'required|numeric',
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
            if (!in_array($category_data, $this->selected_categories) &&
                ($this->is_id_in_saved_cat($category_data['id'], $this->saved_categories) == false) ) {
                array_push($this->selected_categories, $category_data);
            }
        }
    }

    public function mount(Umkm $umkm, Product $product) {
        $this->umkm = $umkm;
        // dd($this->umkm);
        $this->product = $product;
        
        if ($this->umkm == null || $this->product == null) {
            return abort(404);
        }
        if($this->umkm->user_id != Auth::user()->id) {
            return abort(403);
        }

        $this->categories = ProductCategory::get()->all();

        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->stock = $product->stock;
        $this->discount = $product->discount;
        $this->price = $product->price;
        $this->description = $product->description;
        $this->base_delivery_price = $product->base_delivery_price;
        
        if ($product->status != 'revoked') {
            if ($product->status == 'active') {
                $this->status = true;
            }
            else {
                $this->status = false;
            }
            $this->disabled_status = false;
        } else {
            $this->disabled_status = true;
        }
        
        
        $this->displayImage = ProductImage::where('product_id', '=', $product->id)->get()->first();
        if ($this->displayImage) {
            $this->image = $this->displayImage->image;
        } else {
            $this->image = null;
        }

        $saved_categories = ProductCategories::where('product_id', '=', $product->id)->with('product_category')->get()->all();
        $this->saved_categories = $saved_categories;
        $this->saved_categories_to_delete = [];
        
        $this->category = null;
        $this->selected_categories = [];
        $this->image_delete_state = false;

        $this->kec_delivery_price = null;
        $this->selected_kec = null;
        $this->kec_delivery_detail = [];
        $this->delivery_prices = DeliveryPrice::where('product_id', '=', $product->id)->get()->all();
        foreach($this->delivery_prices as $price) {
            array_push($this->kec_delivery_detail, [
                "price" => $price->delivery_price,
                "kecamatan" => $price->location
            ]);
        }

        
        
    }

    public function render()
    {
        return view('livewire.user.edit-product')->layout('layouts.user_settings_app');
    }

    private function is_id_in_saved_cat($id, $saved_categories) {
        foreach ($saved_categories as $cat) {
            if ($cat['product_category']['id'] == $id) {
                return true;
            }
        }
        return false;
    }

    public function empty_image() {
        $this->image = null;
        $this->upload_image = null;
        $this->image_delete_state = true;
    }

    public function remove_saved_category($id) {
        if (!in_array($id, $this->saved_categories_to_delete)) {
            array_push($this->saved_categories_to_delete, $id);
        }

        foreach ($this->saved_categories as $index=>$cat) {
            if ($cat['id'] == $id) {
                unset($this->saved_categories[$index]);
            }
        }
    }

    public function remove_selected_category($cat_id) {
        $cat_data =  $this->find_id_in_cat_array($cat_id, $this->selected_categories);

        if ($cat_data) {
            $key = array_search($cat_data, $this->selected_categories);
            unset($this->selected_categories[$key]);
        }
    }


    public function update_product() {
        $this->validate();

        if ($this->disabled_status == false) {
            if ($this->status == true) {
                $this->product->status = 'active';
            } else {
                $this->product->status = 'disabled';
            }
        }
        
        $this->product->name = ucwords($this->name);
        $this->product->name_slug = Str::slug($this->name);
        $this->product->stock = $this->stock;
        $this->product->discount = $this->discount;
        $this->product->price = $this->price;
        $this->product->description = $this->description;
        $this->product->base_delivery_price = $this->base_delivery_price;
        $this->product->save();

        // Handle Image Upload
        if ($this->upload_image) {
            $product_image = new ProductImage;
            $product_image->image = $this->upload_image->store('product_images');
            $product_image->product_id = $this->product->id;
            $product_image->save();

            if ($this->displayImage) {
                if (file_exists(public_path() . '/storage/'. $this->displayImage->image)) {
                    unlink(public_path() . '/storage/'. $this->displayImage->image);
                }
                $this->displayImage->delete();
                $this->image_delete_state = false;
            }
        }
        if ($this->image_delete_state) {
            if ($this->displayImage) {
                if (file_exists(public_path() . '/storage/'. $this->displayImage->image)) {
                    unlink(public_path() . '/storage/'. $this->displayImage->image);
                }
                $this->displayImage->delete();
            }
        }

        // Handle Select Categories Input
        if ($this->selected_categories) {
            foreach($this->selected_categories as $cat) {
                $product_category = new ProductCategories;
                $product_category->category_id = $cat['id'];
                $product_category->product_id = $this->product->id;
                $product_category->save();
            }
        }

        // Handle Deleting Saved Categories
        if ($this->saved_categories_to_delete) {
            foreach($this->saved_categories_to_delete as $delete_id) {
                $del_product_cat = ProductCategories::where('id', '=', $delete_id)->get()->first();
                if ($del_product_cat) {
                    $del_product_cat->delete();
                }
            }
        }

        // handle Updating Delivery Prices
        foreach($this->delivery_prices as $idx=>$price) {
            $del_delivery_prices_id = $price['id'];
            $del_delivery_prices = DeliveryPrice::find($del_delivery_prices_id);
            $del_delivery_prices->delete();
        }
        if ($this->kec_delivery_detail) {
            foreach($this->kec_delivery_detail as $delivery_detail) {
                $delivery_price = new DeliveryPrice;
                $delivery_price->product_id = $this->product->id;
                $delivery_price->location = $delivery_detail['kecamatan'];
                $delivery_price->delivery_price = $delivery_detail['price'];
                $delivery_price->save();
            }
        }


        return redirect()->route('umkm.profile')->with('message', 'Produk Diupdate');
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
