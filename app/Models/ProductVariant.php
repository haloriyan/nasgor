<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'label', 'price'
    ];

    public function ingredients() {
        return $this->hasMany(ProductIngredient::class, 'variant_id');
    }
}
