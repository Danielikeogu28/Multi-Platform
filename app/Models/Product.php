<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'image',
        'name',
        'description',
        'price',
        'condition',
        'status',
        'vendor_id',
        'product_category_id'
    ];
     public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
