<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class RegisterPage extends Component
{
    // Binding Variable
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $password;
    public $password_confirmation;

    protected function rules() {
        return [
            'username' => 'required|string|unique:users,username,' . $this->username,
            'first_name' => 'required|string',
            'last_name' => 'string',
            'email' => 'required|string|unique:users,email,' . $this->email,
            'phone_number' => 'numeric',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'same:password',
        ];
    }

    // protected $messages = [
    //     'password_confirmation.same' => 'konfirmasi password tidak sama',
    // ];

    

    public function mount() {
        $this->username = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
    
    public function render()
    {
        return view('livewire.register-page')->layout('layouts.app');
    }

    public function register() {
        $this->validate();
        
        $user = new User;

        $generator_rules = [
            'table' => 'users',
            'length' => '9',
            'prefix' => date('ymd'),
        ];
        $id = IdGenerator::generate($generator_rules);
        
        $user->id = $id;
        $user->user_type = 0;
        $user->username = $this->username;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->password = Hash::make($this->password);
        $user->email = $this->email;
        $user->phone_number = $this->phone_number;
        $user->umkm_status = false;
        $user->save();

        if (Auth::loginUsingId($user->id)) {
            return redirect()->route('homepage');
        }
    }

}
