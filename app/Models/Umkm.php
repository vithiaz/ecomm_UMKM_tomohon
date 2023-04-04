<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\UserCart;
use App\Models\ProductImage;
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

}
