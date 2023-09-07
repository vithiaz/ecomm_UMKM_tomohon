<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\UserOrder;
use App\Models\UserOrderItem;
use Illuminate\Support\Facades\Auth;

class TransactionPage extends Component
{
    // Route Binding Variable
    public $status;

    // Model Variable
    public $UserOrder;

    // Binding Variable
    public $refoundId;

    public function mount ($status) {
        $this->status = $status;
        if (!in_array($this->status, [
            'pending',
            'progress',
            'settlement',
            'abort',
        ])) {
            return abort(404);
        }

        $this->UserOrder = UserOrder::with(
            'user',
            'umkm',
            'order_item',
            'refound_order',
        )->where([
            ['buyer_id', '=', Auth::user()->id],
            ['order_status', '=', $this->status],
        ])->get()->sortByDesc('updated_at');

        $this->refoundId = null;

    }

    public function render()
    {
        return view('livewire.user.transaction-page')->layout('layouts.user_settings_app');
    }


    public function get_final_price($basePrice, $discount) {
        if ($discount > 0) {
            $Amount = (int)$basePrice;
            $CalculatedAmount = $Amount - ($Amount * ((float)$discount) / 100);
        } else {
            $CalculatedAmount = (int)$basePrice;
        }
        return $CalculatedAmount;
    }

    public function calculate_amount($price, $qty) {
        return (int)$price * (int)$qty;
    }

    public function get_date($datetime) {
        return date('Y-m-d', strtotime($datetime));
    }

    public function remove_transaction($order) {
        $order_id = $order['id'];
        $order_delete = UserOrder::find($order_id);
        if ($order_delete) {
            $order_delete->delete();
            return redirect(request()->header('Referer'))->with('message', 'Transaksi dihapus');
        }
    }

    public function set_refound_state($order) {
        $this->refoundId = $order['id'];
        
        $this->dispatchBrowserEvent('toggleRefoundModal');
    }

    public function is_delivery($order) {
        foreach($order->order_item as $item) {
            if ($item->delivery_status == 'onsite') {
                return true;
            }
        }
        return false;
    }

}
