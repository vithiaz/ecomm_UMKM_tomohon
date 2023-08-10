<?php

namespace App\Http\Livewire\User;

use App\Models\Umkm;
use Livewire\Component;
use App\Models\BankAccount;
use App\Models\UmkmRegistration;
use Illuminate\Support\Facades\Auth;

class UmkmProfile extends Component
{
    // Model Variable
    public $umkms;

    public function mount() {
        $this->umkms = Umkm::with('success_transaction')
                        ->withCount('success_transaction')
                        ->where('user_id', '=', Auth::user()->id)->get()->all();
    }
    
    public function render()
    {
        return view('livewire.user.umkm-profile')->layout('layouts.user_settings_app');
    }

}
