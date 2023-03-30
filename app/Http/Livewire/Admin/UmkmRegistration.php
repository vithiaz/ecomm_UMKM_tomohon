<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class UmkmRegistration extends Component
{
    // Route Binding Vriable
    public $status;

    public function mount($status) {
        $this->status = $status;
        
        if (!in_array($this->status, [
            'request',
            'acc',
            'rejected',
            'revoked',
        ])) {
            return abort(404);
        }
    }

    public function render()
    {
        return view('livewire.admin.umkm-registration')->layout('layouts.admin_app');
    }
}
