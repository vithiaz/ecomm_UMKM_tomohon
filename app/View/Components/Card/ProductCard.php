<?php

namespace App\View\Components\Card;

use Illuminate\View\Component;

class ProductCard extends Component
{
    
   public $basePrice;
   public $price;
   public $discount;
   public $umkm;
   public $sold;
   public $stock;
   public $productName;
   public $location;
   public $img;
   public $link;
    
    public function __construct(
        $basePrice,
        $discount,
        $umkm,
        $sold,
        $stock,
        $productName,
        $location,
        $img,
        $link
        )
    {
        $this->basePrice = number_format($basePrice, 2, ",", '.');
        $this->discount = $discount;
        $this->umkm = $umkm;
        $this->sold = $this->simplify_number($sold);
        $this->stock = $stock;
        $this->productName = $productName;
        $this->location = $location;
        $this->img = $img;
        $this->link = $link;

        // Calculate Discount
        $Amount = (int)$basePrice;
        $CalculatedAmount = $Amount - ($Amount * ((float)$this->discount) / 100);
        $this->price = number_format($CalculatedAmount, 2, ",", '.');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.product-card');
    }

    public function simplify_number($number) {
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'rb';
        }
        return $number;
    }
    
}
