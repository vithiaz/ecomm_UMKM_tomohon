<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountSettings extends Component
{
    use WithFileUploads;
    
    // Binding variable
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    public $profile_img;
    public $image_upload;

    public $password;
    public $password_confirm;


    public $profile_img_delete_state;


    public function updatedImageUpload() {
        $this->validate([
            'image_upload' => 'nullable|image|max:2048',
        ]);
    }

    protected function rules() {
        return [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
        ];
    }

    public function mount() {
        $this->first_name = Auth::user()->first_name;
        $this->last_name = Auth::user()->last_name;
        $this->email = Auth::user()->email;
        $this->phone = Auth::user()->phone;
        $this->address = Auth::user()->address;
        $this->profile_img = Auth::user()->profile_img;
        $this->image_upload = null;

        $this->password = '';
        $this->password_confirm = '';

        $this->profile_img_delete_state = false;
    }

    public function render()
    {
        return view('livewire.user.account-settings')->layout('layouts.user_settings_app');
    }

    public function update_data() {
        $this->validate();

        $user = User::find(Auth::user()->id);

        $path = null;
        if ($this->profile_img_delete_state) {
            if (file_exists(public_path() . '/storage/'. Auth::user()->profile_img)) {
                unlink(public_path() . '/storage/'. Auth::user()->profile_img);
            }
            $user->profile_img = $path;
        }
        
        if ($this->image_upload) {
            if (Auth::user()->profile_img) {
                if (file_exists(public_path() . '/storage/'. Auth::user()->profile_img)) {
                    unlink(public_path() . '/storage/'. Auth::user()->profile_img);
                }
            }
            $path = $this->image_upload->store('profile_img');
        }

        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->phone_number = $this->phone;
        $user->address = $this->address;
        if ($path) {
            $user->profile_img = $path;
        }
        $user->save();

        $msg = ['success' => 'Data berhasil diubah'];
        $this->dispatchBrowserEvent('display-message', $msg);
    }

    public function update_password() {
        $this->validate([
            'password_confirm' => 'required|same:password',
            'password' => 'required|string|min:8',
        ]);

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($this->password);
        $user->save();

        $msg = ['success' => 'Password diubah!'];
        $this->dispatchBrowserEvent('display-message', $msg);
    }

    public function delete_temporary_img() {
        $this->image_upload = null;
    }

    public function delete_state_profile_img() {
        $this->profile_img = null;
        $this->profile_img_delete_state = true;
    }

}
