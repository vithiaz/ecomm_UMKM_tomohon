<?php

namespace App\Http\Livewire\Base;

use Livewire\Component;

class ProductPage extends Component
{
    public function render()
    {
        return view('livewire.base.product-page')->layout('layouts.app');
    }
}
