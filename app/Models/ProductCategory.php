<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductCategories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_category';

    protected $fillable = [
        'name',
        'name_slug',
    ];

    public function product_categories()
    {
        return $this->hasMany(ProductCategories::class, 'category_id', 'id');
    }

    public function product() {
        return $this->hasManyThrough(
            Product::class,
            ProductCategories::class,
            'category_id',
            'id',
            'id',
            'product_id',
        );
    }

    public function product_active() {
        return $this->hasManyThrough(
            Product::class,
            ProductCategories::class,
            'category_id',
            'id',
            'id',
            'product_id',
        )->where('products.status', '=', 'active');
    }
}
