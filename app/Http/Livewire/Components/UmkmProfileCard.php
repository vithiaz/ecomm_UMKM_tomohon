<?php

namespace App\Http\Livewire\Components;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class UmkmProfileCard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    // Passing Parameters Variable
    public $umkm;

    // Model Variable

    public function mount($umkm) {
        $this->umkm = $umkm;
    }

    public function render()
    {
        $products = Product::where('umkm_id', '=', $this->umkm->id)->paginate(10);
        return view('livewire.components.umkm-profile-card', [
            'products' => $products,
        ]);
    }
}
