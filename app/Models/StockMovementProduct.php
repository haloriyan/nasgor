<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovementProduct extends Model
{
    protected $fillable = [
        'movement_id', 'product_id',
        'price', 'quantity', 'total_price'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function movement() {
        return $this->belongsTo(StockMovement::class, 'movement_id');
    }
}
