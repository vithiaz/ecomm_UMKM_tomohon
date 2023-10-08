<?php

namespace App\Http\Livewire\User;

use App\Models\Umkm;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class RegisterUmkm extends Component
{
    use WithFileUploads;

    // Binding Variable
    public $image;
    public $name;
    public $address;
    public $city;
    public $city_dir;
    public $district;
    public $district_list;

    protected $rules = [
        'name' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string',
        'district' => 'required|string',
    ];

    public function updatedCity() {
        if(array_key_exists($this->city, $this->city_dir)) {
            $this->district_list = $this->city_dir[$this->city];
        }
        $this->district = null;
    }

    public function updatedImage() {
        $this->validate([
            'image' => 'nullable|image|max:2048',
        ]);
    }

    public function mount() {
        $this->image = null;
        $this->name = null;
        $this->address = null;

        $this->city = null;
        $this->district = null;
        $this->district_list = [];

        $this->city_dir = array(
            'Tomohon' => [
                'Taratara',
                'Taratara I',
                'Taratara II',
                'Taratara III',
                'Woloan I',
                'Woloan I Utara',
                'Woloan II',
                'Woloan III',
                'Kampung Jawa',
                'Lahendong',
                'Lansot',
                'Pangolombian',
                'Pinaras',
                'Tumatangtang',
                'Tumatangtang I',
                'Tondangow',
                'Uluindano',
                'Walian',
                'Walian I',
                'Walian II',
                'Kamasi',
                'Kamasi I',
                'Kolongan',
                'Kolongan I',
                'Matani I',
                'Matani II',
                'Matani III',
                'Talete I',
                'Talete II',
                'Kumelembuay',
                'Paslaten I',
                'Paslaten II',
                'Rurukan',
                'Rurukan I',
                'Kakaskasen',
                'Kakaskasen I',
                'Kakaskasen II',
                'Kakaskasen III',
                'Kayawu',
                'Kinilow',
                'Kinilow I',
                'Tinoor I',
                'Tinoor II',
                'Wailan',
            ],
        );

    }

    public function render()
    {
        $districts = $this->district_list;

        return view('livewire.user.register-umkm', [
            'districts' => $districts,
        ])->layout('layouts.user_settings_app');
    }

    public function store_umkm() {
        $this->validate();
        
        $umkm = new Umkm;

        if ($this->image) {
            $path = $this->image->store('umkm_profile_img');
            $umkm->profile_img = $path;
        }

        $generator_rules = [
            'table' => 'umkms',
            'length' => '9',
            'prefix' => date('ymd'),
        ];
        $id = IdGenerator::generate($generator_rules);

        $umkm->id = $id;
        $umkm->name = $this->name;
        $umkm->address = $this->address;
        $umkm->status = true;
        $umkm->city = $this->city;
        $umkm->district = $this->district;
        $umkm->user_id = Auth::user()->id;
        $umkm->save();

        return redirect()->route('umkm.profile')->with('message', 'UMKM ditambahkan');
    }

    public function empty_image() {
        $this->image = null;
    }

}
