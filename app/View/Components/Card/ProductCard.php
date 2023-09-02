<?php

namespace App\View\Components\Card;

use App\Models\UserCart;
use Illuminate\View\Component;

class ProductCard extends Component
{
    
   public $productId;
   public $basePrice;
   public $price;
   public $discount;
   public $umkm;
   public $umkmId;
   public $sold;
   public $stock;
   public $productName;
   public $productNameSlug;
   public $location;
   public $img;
    
    public function __construct(
        $productId,
        $basePrice,
        $discount,
        $umkm,
        $umkmId,
        $sold,
        $stock,
        $productName,
        $productNameSlug,
        $location,
        $img,
        )
    {
        $this->productId = $productId;
        $this->basePrice = number_format($basePrice, 2, ",", '.');
        $this->discount = $discount;
        $this->umkm = $umkm;
        $this->umkmId = $umkmId;
        $this->sold = $this->simplify_number($sold);
        $this->stock = $stock;
        $this->productName = $productName;
        $this->productNameSlug = $productNameSlug;
        $this->location = $location;
        $this->img = $img;

        // Calculate Discount
        $Amount = (int)$basePrice;
        $CalculatedAmount = $Amount - ($Amount * ((float)$this->discount) / 100);
        $this->price = number_format($CalculatedAmount, 2, ",", '.');
    }

    public function render()
    {
        return view('components.card.product-card');
    }

    public function add_to_cart() {
        dd(Auth::check());
        // if (Auth::) {

        // }
        
        // $userCart = new UserCart;
        // $userCart->user_id = 
    }

    public function simplify_number($number) {
        if ($number < 1) {
            return 0;
        }
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'rb';
        }
        return $number;
    }
    
}
