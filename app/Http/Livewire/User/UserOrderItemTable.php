<?php

namespace App\Http\Livewire\User;

use App\Models\UserOrder;
use Illuminate\Support\Str;
use App\Models\UserOrderItem;
use Illuminate\Support\Carbon;
use App\Models\SuccessTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class UserOrderItemTable extends PowerGridComponent
{
    use ActionButton;

    // Binding Variable
    public string $status;

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(), [
                'processDelivery',
                'deliveryOnsite',
            ]);
    }

    private function set_delivery_status($id, $status, $handle_user_order = false) {
        $order_item = UserOrderItem::with('order')->find($id);
        
        if ($order_item) {
            $order_item->delivery_status = $status;
            $order_item->save();

            if ($handle_user_order) {
                // handle UserOrder.order_status is settlement if all order_item.delivery_status is onsite
                $update_user_order = UserOrder::with('order_item')->find($order_item->order->id);
                $is_all_onsite = true;
                foreach($update_user_order->order_item as $update_user_order_item) {
                    if ($update_user_order_item->delivery_status != 'onsite') {
                        $is_all_onsite = false;
                    }
                }
                if ($is_all_onsite) {
                    $update_user_order->order_status = 'settlement';
                    $update_user_order->save();

                    $success_transaction = new SuccessTransaction;
                    $success_transaction->id = Str::uuid()->toString();
                    $success_transaction->order_id = $update_user_order->id;
                    $success_transaction->seller_payment_status = 'pending';
                    $success_transaction->save();
                }
            }

        }
    }

    public function processDelivery(): void
    {
        $checkbox_id = $this->checkboxValues;

        if (count($checkbox_id) == 0) {
            $msg = ['error' => 'Tidak ada order dipilih'];
            $this->dispatchBrowserEvent('display-message', $msg);    
            return ;
        }

        foreach($checkbox_id as $id) {
            $this->set_delivery_status($id, 'processed');
            $this->update_product_stock($id);
        }

        $this->checkboxValues = [];
        $this->fillData();
        $msg = ['success' => 'Pengiriman Diproses'];
        $this->dispatchBrowserEvent('display-message', $msg);    
    }

    // Handle Product Stock after product delivery is proccessed
    private function update_product_stock($order_item_id) {
        $orderItem = UserOrderItem::with('product')->find($order_item_id);
        $product_stock = $orderItem->product->stock;
        $buy_qty = $orderItem->qty;
        if (($product_stock - $buy_qty) >= 0) {
            $orderItem->product->stock = $product_stock - $buy_qty;                
        } else {
            $orderItem->product->stock = 0;
        }
        $orderItem->product->save();
}
    

    public function deliveryOnsite(): void
    {
        $checkbox_id = $this->checkboxValues;

        if (count($checkbox_id) == 0) {
            $msg = ['error' => 'Tidak ada order dipilih'];
            $this->dispatchBrowserEvent('display-message', $msg);    
            return ;
        }

        foreach($checkbox_id as $id) {
            $this->set_delivery_status($id, 'onsite', true);
        }

        $this->checkboxValues = [];
        $this->fillData();
        $msg = ['success' => 'Produk ditandai terkirim'];
        $this->dispatchBrowserEvent('display-message', $msg);
    }

    public function setUp(): array
    {
        if (in_array($this->status, ['pending', 'processed'])) {
            $this->showCheckBox('id');
        }

        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function header(): array
    {
        if ($this->status == 'pending') {
            $header = [
                Button::add('process-delivery')
                    ->caption(__('Proses Pengiriman'))
                    ->class('btn btn-default-orange')
                    ->emit('processDelivery', [])
            ];
        }
        else if ($this->status == 'processed') {
            $header = [
                Button::add('delivery-onsite')
                    ->caption(__('Pengiriman Selesai'))
                    ->class('btn btn-default-orange')
                    ->emit('deliveryOnsite', [])
            ];
        }
        else {
            $header = [];
        }

        return $header;
    }

    public function datasource(): Builder
    {
        return UserOrderItem::query()->with([
                'product',
                'order',
                'order_belongs',
                'order_by',
                'seller_umkm',
                'order_success',
            ])
            ->where('delivery_status', '=', $this->status)
            ->whereHas('seller_umkm', function($q) {
                $q->where('user_id', '=', Auth::user()->id);
            })
            ->whereHas('order_belongs', function ($q) {
                $q->where('payment_status', '=', 'settlement');
            });
    }

    public function relationSearch(): array
    {
        return [
            'product' => [
                'name',
            ],
            'order_by' => [
                'first_name',
                'last_name',
            ],
            'order_success' => [
                'seller_payment_status',
            ]
        ];
    }

    
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('order_success', function (UserOrderItem $model) {
                if ($model->order_success) {
                    return $model->order_success->seller_payment_status;
                }
                else {
                    return '';
                }
            } )
            ->addColumn('user_ordered', function(UserOrderItem $model) {
                $full_name = $model->order_by->first_name;
                if ($model->order_by->last_name) {
                    $full_name = $full_name . ' ' . $model->order_by->last_name;
                }
                return $full_name;
            })
            ->addColumn('product_name', function(UserOrderItem $model) {
                return $model->product->name;
            })
            ->addColumn('qty')
            ->addColumn('amount')
            ->addColumn('amount_formatted', function(UserOrderItem $model) {
                return format_rupiah($model->amount);
            })
            ->addColumn('message')
            ->addColumn('address', function(UserOrderItem $model) {
                return $model->order->order_address;
            })
            ->addColumn('updated_at')
            ->addColumn('updated_at_formatted', fn (UserOrderItem $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'));
    }


    public function columns(): array
    {
        if ($this->status == 'onsite') {
            $columns_return = [
                Column::make('ID', 'id')
                    ->searchable()
                    ->hidden(),
        
                Column::make('Status Pembayaran', 'order_success')
                    ->searchable(),
        
                Column::make('Pemesan', 'user_ordered')
                    ->searchable(),
             
                Column::make('Produk', 'product_name')
                    ->searchable(),
        
                Column::make('Qty', 'qty')
                    ->sortable(),
        
                Column::make('Harga Satuan', 'amount_formatted', 'amount')
                    ->sortable(),
        
                Column::make('Catatan', 'message')
                    ->searchable(),
        
                Column::make('Alamat Pengiriman', 'address')
                    ->searchable(),
        
                Column::make('Tanggal', 'updated_at_formatted', 'updated_at')
                    ->sortable(),
            ];
        } else {
            $columns_return = [
                Column::make('ID', 'id')
                    ->searchable()
                    ->hidden(),
                
                Column::make('Pemesan', 'user_ordered')
                    ->searchable(),
             
                Column::make('Produk', 'product_name')
                    ->searchable(),
        
                Column::make('Qty', 'qty')
                    ->sortable(),
        
                Column::make('Harga Satuan', 'amount_formatted', 'amount')
                    ->sortable(),
        
                Column::make('Catatan', 'message')
                    ->searchable(),
        
                Column::make('Alamat Pengiriman', 'address')
                    ->searchable(),
        
                Column::make('Tanggal', 'updated_at_formatted', 'updated_at')
                    ->sortable(),
            ];
        }


        return $columns_return;
    }

    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            // Filter::inputText('name'),
            // Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid UserOrderItem Action Buttons.
     *
     * @return array<int, Button>
     */

    /*
    public function actions(): array
    {
       return [
           Button::make('edit', 'Edit')
               ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
               ->route('user-order-item.edit', ['user-order-item' => 'id']),

           Button::make('destroy', 'Delete')
               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
               ->route('user-order-item.destroy', ['user-order-item' => 'id'])
               ->method('delete')
        ];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid UserOrderItem Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($user-order-item) => $user-order-item->id === 1)
                ->hide(),
        ];
    }
    */
}
