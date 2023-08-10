<?php

namespace App\Http\Livewire\Components;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserOrderItem;

class UmkmProfileCard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    // Passing Parameters Variable
    public $umkm;

    // Model Variable

    public function mount($umkm) {
        $this->umkm = $umkm;
    }

    public function render()
    {
        $products = Product::with('order_item')->where('umkm_id', '=', $this->umkm->id)->paginate(10);
        return view('livewire.components.umkm-profile-card', [
            'products' => $products,
        ]);
    }

    public function get_success_transaction($order_item) {
        $success_trans_count = 0;
        if ($order_item->count() > 0) {

            foreach ($order_item as $order) {
                $userOrderItem = UserOrderItem::withCount('order_success')->find($order->id);
                $success_trans_count += $userOrderItem->order_success_count;
            }
        }
        return $success_trans_count;
    }
}
