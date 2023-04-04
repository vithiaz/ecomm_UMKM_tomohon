<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class UmkmAccountVerification extends Component
{
    // Route Binding Variable
    public $status;

    public function mount($status) {
        $this->status = $status;

        if (!in_array($this->status, [
            'request',
            'acc',
            'revoked',
            'rejected'
        ])) {
            return abort(404);
        }

    }

    public function render()
    {
        return view('livewire.admin.umkm-account-verification')->layout('layouts.admin_app');
    }
}
