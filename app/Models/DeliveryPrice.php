<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPrice extends Model
{
    use HasFactory;

    protected $table = 'delivery_prices';

    protected $fillable = [
        'product_id',
        'location',
        'delivery_price',
    ];
}
