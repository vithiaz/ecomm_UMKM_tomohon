<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $username;
    public $password;

    protected $rules = [
        'username' => 'required|string',
        'password' => 'required',
    ];

    protected $messages = [
        'username.required' => 'Username tidak boleh kosong.',
        'password.required' => 'Password tidak boleh kosong.',
    ];

    public function render()
    {
        return view('livewire.components.login');
    }

    public function login() {
        $this->validate();
        $remember_me = true;

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $remember_me)) {
            return redirect(request()->header('Referer'));
        }
        else {
            $this->password = '';
            session()->flash('error', 'Username atau password salah!');
        }
    }
}
