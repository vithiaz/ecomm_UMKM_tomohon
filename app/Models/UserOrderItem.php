<?php

namespace App\Models;

use App\Models\Umkm;
use App\Models\User;
use App\Models\Product;
use App\Models\UserOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOrderItem extends Model
{
    use HasFactory;

    protected $table = 'user_order_items';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'qty',
        'amount',
        'delivery_status',
        'message',
    ];
    
    public function order()
    {
        return $this->hasOne(UserOrder::class, 'id', 'order_id');
    }

    public function order_belongs()
    {
        return $this->belongsTo(UserOrder::class, 'order_id');
    }

    public function order_by()
    {
        return $this->hasOneThrough(
            User::class,
            UserOrder::class,
            'id',
            'id',
            'order_id',
            'buyer_id',
        );
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function seller_umkm() {
        return $this->hasOneThrough(
            Umkm::class,
            Product::class,
            'id',
            'id',
            'product_id',
            'umkm_id',
        );
    }

}
