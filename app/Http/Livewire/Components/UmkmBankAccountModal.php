<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class UmkmBankAccountModal extends Component
{

    // Binding variable
    public $umkmDetail;
    public $userDetail;
    public $bankAccounts;

    public function mount() {
        $this->umkmDetail = [];
        $this->userDetail = [];        
        $this->bankAccounts = [];
    }

    public function render()
    {
        return view('livewire.components.umkm-bank-account-modal');
    }
}
