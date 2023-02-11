<?php

namespace App\Http\Livewire\Base;

use Livewire\Component;

class Homepage extends Component
{
    public function render()
    {
        return view('livewire.base.homepage')->layout('layouts.app');
    }
}
