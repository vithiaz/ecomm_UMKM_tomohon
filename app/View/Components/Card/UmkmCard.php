<?php

namespace App\View\Components\Card;

use Illuminate\View\Component;

class UmkmCard extends Component
{
    
    public $image;
    public $name;
    public $location;
    public $sold;
    public $link;


    public function __construct(
        $image,
        $name,
        $location,
        $sold,
        $link
    )
    {
        $this->image = $image;
        $this->name = $name;
        $this->location = $location;
        $this->sold = $this->simplify_number($sold);
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.umkm-card');
    }

    public function simplify_number($number) {
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'rb';
        }
        return $number;
    }
}
