<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrderItem extends Model
{
    use HasFactory;

    protected $table = 'user_order_items';

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'qty',
        'amount',
        'delivery_status',
        'message',
    ];
}
