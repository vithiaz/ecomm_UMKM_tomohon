<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Carbon;

class SellingProducts extends Component
{
    // Model Variable
    public $Products;

    // Binding Variable
    public $date_filter_start;
    public $date_filter_end;

    public $load_products = 20;
    private $load_products_increment = 20;
    public $all_loaded_state;

    public function mount() {
        $date_filter = Carbon::now();
        $this->date_filter_start = '';
        $this->date_filter_end = '';
    }

    public function updatedDateFilterStart(){
        $this->set_loaded_state();
        $this->load_products = 20;
    }
    public function updatedDateFilterEnd(){
        $this->set_loaded_state();
        $this->load_products = 20;
    }

    public function render()
    {
        $Products = Product::with([
            'umkm',
        ]);

        if ($this->date_filter_start) {
            if ($this->date_filter_end) {
                $Products = $Products->with('order_item.order_success', function ($q) {
                                $q->where([
                                    ['success_transactions.created_at', '<=', $this->date_filter_end],
                                    ['success_transactions.created_at', '>=', $this->date_filter_start],
                                ]);
                            })
                            ->whereHas('order_item.order_success', function ($q) {
                                $q->where([
                                    ['success_transactions.created_at', '<=', $this->date_filter_end],
                                    ['success_transactions.created_at', '>=', $this->date_filter_start],
                                ]);
                            });
            } else {
                $Products = $Products->with('order_item.order_success', function ($q) {
                                $q->where('success_transactions.created_at', '>=', $this->date_filter_start);
                            })
                            ->whereHas('order_item.order_success', function ($q) {
                                $q->where('success_transactions.created_at', '>=', $this->date_filter_start);
                            });
            }
        }

        if ($this->date_filter_end) {
            if ($this->date_filter_start) {
                $Products = $Products->with('order_item.order_success', function ($q) {
                                $q->where([
                                    ['success_transactions.created_at', '<=', $this->date_filter_end],
                                    ['success_transactions.created_at', '>=', $this->date_filter_start],
                                ]);
                            })
                            ->whereHas('order_item.order_success', function ($q) {
                                $q->where([
                                    ['success_transactions.created_at', '<=', $this->date_filter_end],
                                    ['success_transactions.created_at', '>=', $this->date_filter_start],
                                ]);
                            });
            } else {
                $Products = $Products->with('order_item.order_success', function ($q) {
                                $q->where('success_transactions.created_at', '<=', $this->date_filter_end);
                            })
                            ->whereHas('order_item.order_success', function ($q) {
                                $q->where('success_transactions.created_at', '<=', $this->date_filter_end);
                            });
            }
        }


        if (!$this->date_filter_end && !$this->date_filter_start) {
            $Products->with('order_item.order_success')->whereHas('order_item.order_success');
        }

        $Products = $Products->get()->toArray();

        
        // Count Success Order
        $this->Products = $this->sortby_success_order($Products);
        $this->set_loaded_state();

        $products = array_slice($this->Products, 0, $this->load_products);
        return view('livewire.admin.selling-products', ['products' => $products])->layout('layouts.admin_app');
    }

    
    private function sortby_success_order($Products) {
        foreach ($Products as $index=>$product) {
            $selling_qty = 0;

            if ($product['order_item']) {

                foreach ($product['order_item'] as $order_item) {
                    if ($order_item['order_success']) {
                        $selling_qty += $order_item['qty'];
                    }
                }
            }
            $Products[$index]['success_order_count'] = $selling_qty;
        }

        $success_order_count = array_column($Products, 'success_order_count');
        array_multisort($success_order_count, SORT_DESC, $Products);

        return $Products;
    }

    private function set_loaded_state() {
        if ($this->load_products >= count($this->Products)) {
            $this->all_loaded_state = true;
            $this->load_products = count($this->Products);
        }
        else {
            $this->all_loaded_state = false;
        }
    }

    public function load_more() {
        if (($this->load_products + $this->load_products_increment) <= count($this->Products)) {
            $this->load_products += $this->load_products_increment;
        } else {
            $this->load_products += (count($this->Products) - $this->load_products);
        }
        $this->set_loaded_state();
    }

    
    public function reset_date_filter() {
        $this->set_loaded_state();
        $this->date_filter_start = '';
        $this->date_filter_end = '';
        $this->load_products = 20;
    }




}
