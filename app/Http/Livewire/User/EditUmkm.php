<?php

namespace App\Http\Livewire\User;

use App\Models\Umkm;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class EditUmkm extends Component
{
    use WithFileUploads;

    // Route Variable
    public $umkm;

    // Binding Variable
    public $image;
    public $upload_image;
    public $status;
    public $name;
    public $address;
    public $city;
    public $city_dir;
    public $district;
    public $district_list;

    public $image_delete_state;

    protected $rules = [
        'status' => 'required|boolean',
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

    public function updatedUploadImage() {
        $this->validate([
            'image' => 'nullable|image|max:2048',
        ]);
    }

    public function mount(Umkm $umkm) {
        $this->umkm = $umkm;

        if ($this->umkm == null) {
            return abort(404);
        }
        if($this->umkm->user_id != Auth::user()->id) {
            return abort(403);
        }

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

        $this->image = $this->umkm->profile_img;
        $this->upload_image = null;
        $this->status = $this->umkm->status;
        $this->name = $this->umkm->name;
        $this->address = $this->umkm->address;
        $this->city = $this->umkm->city;
        $this->district = $this->umkm->district;

        $this->image_delete_state = false;

        if(array_key_exists($this->city, $this->city_dir)) {
            $this->district_list = $this->city_dir[$this->city];
        } else {
            $this->district_list = [];
        }
    }

    public function render()
    {
        $districts = $this->district_list;
        return view('livewire.user.edit-umkm', [
            'districts' => $districts,
        ])->layout('layouts.user_settings_app');
    }

    public function update_umkm() {
        $this->validate();

        $this->umkm->status = $this->status;
        $this->umkm->name = $this->name;
        $this->umkm->city = $this->city;
        $this->umkm->district = $this->district;
        $this->umkm->address = $this->address;

        if ($this->image_delete_state) {
            if ($this->umkm->profile_img) {
                if (file_exists(public_path() . '/storage/'. $this->umkm->profile_img)) {
                    unlink(public_path() . '/storage/'. $this->umkm->profile_img);
                }
                $this->umkm->profile_img = null;
            }
        }

        if ($this->upload_image) {
            $this->umkm->profile_img = $this->upload_image->store('umkm_profile_img');
        }
        $this->umkm->save();

        return redirect()->route('umkm.profile')->with('message', 'UMKM Diupdate');
    }

    public function empty_image() {
        $this->image = null;
        $this->upload_image = null;
        $this->image_delete_state = true;
    }
}
