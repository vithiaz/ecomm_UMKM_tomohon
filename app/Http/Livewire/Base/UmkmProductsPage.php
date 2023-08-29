<?php

namespace App\Http\Livewire\Base;

use App\Models\Umkm;
use App\Models\Product;
use Livewire\Component;

class UmkmProductsPage extends Component
{
    // Route Binding Variable
    public $umkm_id;

    // Binding Variable
    public $umkm;
    public $load_count;
    public $load_count_increment = 8;
    public $all_loaded_state;

    
    public function mount($umkm_id) {
        $this->umkm_id = $umkm_id;
        
        $this->umkm = Umkm::find($umkm_id);

        if (!$this->umkm) {
            return abort(404);
        }
        
        $this->load_count = 12;
        $this->all_loaded_state = false;
    }

    public function render()
    {
        $get_other_product = Product::with([
            'profile_image',
            'umkm',
        ])
        ->where([
            ['umkm_id', '=', $this->umkm->id],
            ['status', '=', 'active'],
        ])
        ->get();

        $other_product = $get_other_product->take($this->load_count);

        if ($this->load_count >= count($get_other_product)) {
            $this->all_loaded_state = true;
        }

        return view('livewire.base.umkm-products-page',[
            'other_product' => $other_product,
        ]);
    }

    public function load_more() {
        $this->load_count += $this->load_count_increment;
    }

}
