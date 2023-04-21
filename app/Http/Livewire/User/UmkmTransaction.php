<?php

namespace App\Http\Livewire\User;

use App\Models\Umkm;
use Livewire\Component;
use App\Models\UserOrder;
use App\Models\UserOrderItem;
use Illuminate\Support\Facades\Auth;

class UmkmTransaction extends Component
{
    // Route Binding Variable
    public $status;

    // Model Variable
    public $Umkm;
    public $PaymentStatus;

    public function mount($status) {
        $this->status = $status;

        if (!in_array($this->status, [
            'pending',
            'processed',
            'onsite',
            'return',
        ])) {
            return abort(404);
        }

        $this->PaymentStatus = UserOrder::where('order_status', '=', $this->status)->get()->first();
        $this->Umkm = Umkm::with('user')->whereHas('user', function ($query) {
            return $query->where('id', '=', Auth::user()->id);
        })->get();
    }

    public function render()
    {
        return view('livewire.user.umkm-transaction')->layout('layouts.user_settings_app');
    }
}
