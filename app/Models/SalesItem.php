<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    protected $fillable = [
        'sales_id', 'product_id', 'price_id',
        'price', 'quantity', 'total_price', 'additional_price', 'grand_total',
        'notes', 'margin', 'total_margin'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function price_data() {
        return $this->belongsTo(ProductPrice::class, 'price_id');
    }
    public function addons() {
        return $this->hasMany(SalesItemAddon::class, 'item_id');
    }
}
