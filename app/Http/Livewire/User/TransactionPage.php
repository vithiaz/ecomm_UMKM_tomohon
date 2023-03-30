<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class TransactionPage extends Component
{
    public function render()
    {
        return view('livewire.user.transaction-page')->layout('layouts.user_settings_app');
    }
}
