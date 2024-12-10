<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function product_stock()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function product_ratings()
    {
        return $this->hasMany(ProductRating::class)->where('status', 1);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
