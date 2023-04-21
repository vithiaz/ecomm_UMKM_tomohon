<?php

namespace App\Models;

use App\Models\Umkm;
use App\Models\User;
use App\Models\UserOrderItem;
use App\Models\SuccessTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOrder extends Model
{
    use HasFactory;
    
    protected $table = 'user_orders';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'buyer_id',
        'umkm_id',
        'order_address',
        'payment_status',
        'order_status',
        'payment_amount',
        'payment_token',
    ];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'buyer_id');
    }

    public function umkm()
    {
        return $this->hasOne(Umkm::class, 'id', 'umkm_id');
    }

    public function order_item()
    {
        return $this->hasMany(UserOrderItem::class, 'order_id', 'id');
    }

    public function success_transaction()
    {
        return $this->hasOne(SuccessTransaction::class, 'order_id', 'id');
    }


}
