<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class CartPage extends Component
{
    public function render()
    {
        return view('livewire.user.cart-page')->layout('layouts.user_settings_app');
    }
}
