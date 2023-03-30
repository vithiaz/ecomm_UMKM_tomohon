<?php

namespace App\Http\Livewire\Base;

use Livewire\Component;

class UmkmPage extends Component
{
    public function render()
    {
        return view('livewire.base.umkm-page')->layout('layouts.app');
    }
}
