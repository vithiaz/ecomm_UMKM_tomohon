<?php

namespace App\Models;

use App\Models\Umkm;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductCategories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'name_slug',
        'stock',
        'discount',
        'price',
        'description',
        'status',
        'umkm_id',
    ];

    public function product_categories()
    {
        return $this->hasMany(ProductCategories::class, 'product_id', 'id');
    }

    public function profile_image()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function umkm()
    {
        return $this->hasOne(Umkm::class, 'id', 'umkm_id');
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Umkm::class,
            'id',
            'id',
            'umkm_id',
            'user_id',
        );
    }

    public function categories()
    {
        return $this->hasManyThrough(
            ProductCategory::class,
            ProductCategories::class,
            'product_id',
            'id',
            'id',
            'category_id',
        );
    }

}
