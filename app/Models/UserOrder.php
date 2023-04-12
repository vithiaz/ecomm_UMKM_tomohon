<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    use HasFactory;
    
    protected $table = 'user_orders';

    protected $fillable = [
        'id',
        'buyer_id',
        'umkm_id',
        'order_address',
        'payment_status',
        'payment_amount',
    ];

}
