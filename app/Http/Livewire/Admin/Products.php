<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Products extends Component
{
    // Route Binding Variable
    public $status;

    // Binding Variable
    // 

    public function mount($status) {
        $this->status = $status;

        if (!in_array($this->status, [
            'active',
            'revoked',
        ])) {
            return abort(404);
        }
    }

    public function render()
    {
        return view('livewire.admin.products')->layout('layouts.admin_app');
    }

}
