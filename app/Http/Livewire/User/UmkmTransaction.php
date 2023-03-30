<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class UmkmTransaction extends Component
{
    public function render()
    {
        return view('livewire.user.umkm-transaction')->layout('layouts.user_settings_app');
    }
}
