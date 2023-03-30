<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\UmkmRegistration;
use Illuminate\Support\Facades\Validator;

class UmkmRegistrationReview extends Component
{
    // Route Binding Variable
    public $user_id;
    public $reg_id;

    // Model Variable
    public $umkm_registration;

    // Binding Variable
    public $status;
    public $message;
    public $decline_message;

    protected $rules = [
        'status' => 'required',
        'message' => 'nullable|string',
    ];

    public function mount($user_id, $reg_id) {
        $this->user_id = $user_id;
        $this->reg_id = $reg_id;

        $this->umkm_registration = UmkmRegistration::with('user')->where([
            ['id', '=', $this->reg_id],
            ['user_id', '=', $this->user_id],
        ])->get()->first();

        if ($this->umkm_registration == null) {
            return abort(404);
        }

        $this->status = $this->umkm_registration->status;
        $this->message = $this->umkm_registration->message;
        $this->decline_message = '';
    }

    public function render()
    {
        return view('livewire.admin.umkm-registration-review', ['user' => $this->umkm_registration->user])->layout('layouts.admin_app');;
    }

    public function set_active_umkm_status() {
        $this->umkm_registration->status = 'acc';
        $this->umkm_registration->message = '';
        $this->umkm_registration->save();
        $this->umkm_registration->user->umkm_status = true;
        $this->umkm_registration->user->save();

        return redirect()->route('admin.umkm-registration', ['status' => 'acc'])->with('message', 'Aktivasi UMKM Berhasil');
    }

    public function decline_umkm_status_request() {
        Validator::make(
            ['decline_message' => $this->decline_message],
            ['decline_message' => 'nullable'],
        )->validate();

        $this->umkm_registration->status = 'rejected';
        $this->umkm_registration->message = $this->decline_message;
        $this->umkm_registration->save();
        $this->umkm_registration->user->umkm_status = false;
        $this->umkm_registration->user->save();
        return redirect()->route('admin.umkm-registration', ['status' => 'rejected'])->with('message', 'Aktivasi UMKM Ditolak');
    }


    public function update_umkm_status() {
        $this->validate();

        if ($this->status == 'revoked') {
            $this->umkm_registration->user->umkm_status = false;
            $this->umkm_registration->message = $this->message;
            $this->umkm_registration->user->save();
        } else {
            $this->umkm_registration->user->umkm_status = true;
            $this->umkm_registration->message = '';
            $this->umkm_registration->user->save();
        }

        $this->umkm_registration->status = $this->status;
        $this->umkm_registration->save();

        return redirect()->route('admin.umkm-registration', ['status' => $this->status])->with('message', 'Status UMKM user diubah');
    }

}
