<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;
    
    protected $table = 'user_carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'qty',
    ];

    public function umkm()
    {
        return $this->hasOneThrough(
            Umkm::class,
            Product::class,
            'id',
            'id',
            'product_id',
            'umkm_id'
        );
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

}
