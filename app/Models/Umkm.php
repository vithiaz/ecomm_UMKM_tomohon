<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\UserCart;
use App\Models\UserOrder;
use App\Models\BankAccount;
use App\Models\ProductImage;
use App\Models\SuccessTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkms';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'status',
        'city',
        'district',
        'address',
        'profile_img',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'umkm_id', 'id');
    }
    
    public function order()
    {
        return $this->hasMany(UserOrder::class, 'umkm_id', 'id');
    }

    public function success_transaction()
    {
        return $this->hasManyThrough(
            SuccessTransaction::class,
            UserOrder::class,
            'umkm_id',
            'order_id',
            'id',
            'id',
        );
    }

    public function bank_accounts()
    {
        return $this->hasManyThrough(
            BankAccount::class,
            User::class,
            'id',
            'user_id',
            'user_id',
            'id',
        );
    }


}
