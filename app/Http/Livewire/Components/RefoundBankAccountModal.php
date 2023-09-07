<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class RefoundBankAccountModal extends Component
{

    // Binding Variable
    public $userDetail;
    public $refoundDetail;

    public function mount() {
        $this->userDetail = null;
        $this->refoundDetail = null;
    }

    public function render()
    {
        return view('livewire.components.refound-bank-account-modal');
    }
}
