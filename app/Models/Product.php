<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'branch_id', 'name', 'slug', 'description', 'price', 'quantity', 'priority'
    ];

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function prices() {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }
    public function variants() {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
    public function ingredients() {
        return $this->hasMany(ProductIngredient::class, 'product_id');
    }
    public function addons() {
        return $this->hasMany(ProductAddOn::class, 'product_id');
    }
    public function categories() {
        return $this->belongsToMany(Category::class, 'product_categories');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    // public function products() {
    //     return $this->belongsToMany(Product::class, 'product_categories');
    // }
}
