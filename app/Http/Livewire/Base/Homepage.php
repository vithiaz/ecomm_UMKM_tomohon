<?php

namespace App\Http\Livewire\Base;

use App\Models\Product;
use Livewire\Component;
use App\Models\UserOrder;
use App\Models\UserOrderItem;

class Homepage extends Component
{
    // Model Variable
    public $Products;
    public $userOrder;

    // Binding Variable
    public $hero_product_id;

    public function mount() {
        $this->Products = Product::with([
            'profile_image',
            'umkm',
        ])
        ->where('status', '=', 'active')
        ->get()->toArray();

        $this->userOrder = UserOrder::with([
                                        'success_transaction',
                                    ])
                                    ->withCount('success_transaction')
                                    ->get();

        $userOrderItem = UserOrderItem::with('order_belongs')->get()->groupBy('product_id');

        $hero_product = [];
        foreach(array_keys($userOrderItem->toArray()) as $product_id) {
            $product_data = [];

            foreach ($this->Products as $p) {
                if ($p['id'] == $product_id) {
                    $product_data = $p;
                }
            }

            if ($product_data) {
                // $success_order_count = 0;
                $sales_qty = 0;
                foreach($userOrderItem[$product_id] as $order_item) {
                    $user_order_belongs = $order_item->order_belongs()->withCount('success_transaction')->get();
                    foreach ($user_order_belongs as $item_to_user_orders) {
                        $transaction_count = $item_to_user_orders->success_transaction_count;
                        // $success_order_count += $transaction_count;
                        if ($transaction_count > 0) {
                            $sales_qty += $order_item['qty'];
                        }
                    }
                }
    
                if ($sales_qty > 0) {
                    $product_data['sales_qty'] = $sales_qty;
                    array_push($hero_product, $product_data);
                }
                // if ($success_order_count > 0) {
                //     $product_data['success_order_count'] = $success_order_count;
                // }
            }
        }

        // Sorting hero_product
        usort($hero_product, function ($a, $b) {
            if ($a['sales_qty'] == $b['sales_qty']) {
                return 0;
            }
            return ($a['sales_qty'] > $b['sales_qty']) ? -1 : 1;
        });

        // Specified limit number of hero_product;
        $hero_product = array_slice($hero_product, 0, 16);
        $this->hero_product_id = [];
        foreach($hero_product as $product) {
            array_push($this->hero_product_id, $product['id']);
        }
    }

    public function render()
    {
        return view('livewire.base.homepage')->layout('layouts.app');
    }

    public function get_product_model($product_id) {
        return Product::find($product_id);
    }

}
