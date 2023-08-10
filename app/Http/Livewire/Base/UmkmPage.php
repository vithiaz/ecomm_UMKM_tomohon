<?php

namespace App\Http\Livewire\Base;

use App\Models\Umkm;
use Livewire\Component;
use App\Models\UserCart;
use Illuminate\Support\Facades\Auth;

class UmkmPage extends Component
{
    // Model Variable
    protected $umkmQuery;
    public $popularUmkm;
    public $popularUmkmIds;

    // Binding Variable
    public $load_count;
    public $load_count_increment = 1;
    public $all_loaded_state;

    public function mount() {
        $this->umkmQuery = Umkm::with(['order', 'success_transaction'])
                                ->withCount('success_transaction')
                                ->where('status', '=', true);
        $this->popularUmkm = $this->umkmQuery->whereHas('success_transaction')->get()->sortByDesc('success_transaction_count')->take(2);

        $this->popularUmkmIds = [];
        foreach ($this->popularUmkm as $umkm) {
            array_push($this->popularUmkmIds, $umkm->id);
        }

        $this->load_count = 12;
        $this->all_loaded_state = false;
    }

    public function render()
    {
        $get_other_umkm = Umkm::with(['order', 'success_transaction'])
                            ->withCount('success_transaction')
                            ->where('status', '=', true)
                            ->whereNotIn('id', $this->popularUmkmIds)->get();
        $other_umkm = $get_other_umkm->take($this->load_count);

        if ($this->load_count >= count($get_other_umkm)) {
            $this->all_loaded_state = true;
        }

        return view('livewire.base.umkm-page', ['other_umkm' => $other_umkm])->layout('layouts.app');
    }

    public function load_more() {
        $this->load_count += 8;
    }

}
