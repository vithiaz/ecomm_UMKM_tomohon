<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\UserOrder;
use Illuminate\Support\Str;
use App\Models\RefoundOrder;
use Illuminate\Support\Facades\Auth;

class RefoundTransaction extends Component
{
    // Route Binding Variable
    public $transaction_id;

    // Model Variable
    public $order_detail;

    // Binding Variable
    public $bank_name;
    public $account_number;
    public $account_name;
    public $refound_description;

    protected $rules = [
        'bank_name' => 'required',
        'account_number' => 'required|string',
        'account_name' => 'required|string',
        'refound_description' => 'required|string',
    ];

    protected $messages = [
        'bank_name.required' => 'nama bank harus diisi',
        'account_number.required' => 'nomor rekening harus diisi',
        'account_name.required' => 'nama pemilik rekening harus diisi',
        'refound_description.required' => 'mohon sertakan keterangan atau alasan melakukan refound',
    ];

    public function mount($transaction_id) {
        $this->transaction_id = $transaction_id;

        $this->order_detail = UserOrder::with([
            'user',
            'umkm',
            'order_item',
        ])->find($transaction_id);

        if (!$this->order_detail) {
            return abort(404);
        }
        if ($this->order_detail->buyer_id != Auth::user()->id) {
            return abort(403);
        }

        $this->bank_name = '';
        $this->account_number = '';
        $this->account_name = '';
        $this->refound_description = '';
    }


    public function render()
    {
        return view('livewire.user.refound-transaction')->layout('layouts.user_settings_app');
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

    public function request_refound() {
        $this->validate();
        
        // Update Order Status
        $this->order_detail->order_status = 'abort';
        $this->order_detail->save();

        // Update Order items delivery status
        foreach($this->order_detail->order_item as $order_item) {
            $order_item->delivery_status = 'return';
            $order_item->save();
        }

        // Create Refound Order Table
        $RefoundOrder = new RefoundOrder;
        $RefoundOrder->id = Str::uuid()->toString();
        $RefoundOrder->order_id = $this->order_detail->id;
        $RefoundOrder->bank_name = $this->bank_name;
        $RefoundOrder->account_number = $this->account_number;
        $RefoundOrder->account_name = $this->account_name;
        $RefoundOrder->payment_status = 'pending';
        $RefoundOrder->refound_description = $this->refound_description;
        $RefoundOrder->save();

        return redirect()->route('transaction-page', ['status' => 'abort'])->with('message', 'refound dalam pengajuan');
    }


}
