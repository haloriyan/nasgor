<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductIngredient extends Model
{
    protected $fillable = [
        'product_id', 'ingredient_id', 'quantity'
    ];

    public function ingredient() {
        return $this->belongsTo(Product::class, 'ingredient_id');
    }
}
