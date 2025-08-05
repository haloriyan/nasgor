<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasingProduct extends Model
{
    protected $fillable = [
        'purchasing_id', 'product_id',
        'quantity', 'price', 'total_price'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
