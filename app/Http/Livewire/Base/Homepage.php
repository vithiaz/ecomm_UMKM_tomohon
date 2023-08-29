<?php

namespace App\Http\Livewire\Base;

use App\Models\Umkm;
use App\Models\Product;
use Livewire\Component;
use App\Models\UserCart;
use App\Models\UserOrder;
use Livewire\WithPagination;
use App\Models\UserOrderItem;
use Illuminate\Support\Facades\Auth;

class Homepage extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Model Variable
    protected $ProductsBase;
    public $Products;
    public $userOrder;
    protected $umkmQuery;
    public $popularUmkm;

    // Binding Variable
    public $hero_product;
    public $load_count;
    public $load_count_increment = 8;
    public $all_loaded_state;

    protected $listeners = ['storeCart' => 'store_user_cart'];

    public function mount() {

        $this->ProductsBase = Product::with([
            'profile_image',
            'umkm',
        ])
        ->where('status', '=', 'active')
        ->whereHas('umkm', function ($model) {
            return $model->where('status', '=', true);
        });
    
        $this->Products = $this->ProductsBase->get()->toArray();
    
        $this->userOrder = UserOrder::with([
                    'success_transaction',
                ])
                ->withCount('success_transaction')
                ->get();
    
        $userOrderItem = UserOrderItem::with('order_belongs')->get()->groupBy('product_id');
    
        $this->umkmQuery = Umkm::with(['order', 'success_transaction'])->withCount('success_transaction')->where('status', '=', true);
        $this->popularUmkm = $this->umkmQuery->whereHas('success_transaction')->get()->sortByDesc('success_transaction_count')->take(8);

        $hero_product = [];
        foreach(array_keys($userOrderItem->toArray()) as $product_id) {
            $product_data = [];
    
            foreach ($this->Products as $p) {
                if ($p['id'] == $product_id) {
                    $product_data = $p;
                }
            }
    
            if ($product_data) {
                $sales_qty = 0;
                foreach($userOrderItem[$product_id] as $order_item) {
                    $user_order_belongs = $order_item->order_belongs()->withCount('success_transaction')->get();
                    foreach ($user_order_belongs as $item_to_user_orders) {
                        $transaction_count = $item_to_user_orders->success_transaction_count;
                        if ($transaction_count > 0) {
                            $sales_qty += $order_item['qty'];
                        }
                    }
                }
    
                if ($sales_qty > 0) {
                    $product_data['sales_qty'] = $sales_qty;
                    array_push($hero_product, $product_data);
                }
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
        $this->hero_product = $hero_product;

        $this->load_count = 12;
        $this->all_loaded_state = false;
    }
    
    public function render()
    {
        $get_other_product = Product::with([
                            'profile_image',
                            'umkm',
                        ])
                        ->where('status', '=', 'active')
                        ->whereHas('umkm', function ($model) {
                            return $model->where('status', '=', true);
                        })
                        ->get();

        $other_product = $get_other_product->take($this->load_count);

        if ($this->load_count >= count($get_other_product)) {
            $this->all_loaded_state = true;
        }
        
        return view('livewire.base.homepage', [
            'other_product' => $other_product,
        ])->layout('layouts.app');
    }

    public function load_more() {
        $this->load_count += 8;
    }

    public function store_user_cart($product_id) {
        if(Auth::check()) {
            
            $cart = UserCart::where([
                ['user_id', '=', Auth::user()->id],
                ['product_id', '=', $product_id],
            ])->get()->first();

            if ($cart != null) {
                $cart->qty += 1;
                $cart->save();
            }
            else {
                $newCart = new UserCart;
                $newCart->user_id = Auth::user()->id;
                $newCart->product_id = $product_id;
                $newCart->qty = 1;
                $newCart->save();
            }            
            
            $msg = ['success' => 'Ditambahkan ke keranjang'];
            $this->dispatchBrowserEvent('display-message', $msg);
        }
    }

}
