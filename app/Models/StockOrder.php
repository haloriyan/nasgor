<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOrder extends Model
{
    protected $fillable = [
        'seeker_branch_id', 'seeker_id', 'taker_id', 'product_id', 
        'price', 'quantity', 'total_price', 'status', 'date'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function seeker_branch() {
        return $this->belongsTo(Branch::class, 'seeker_branch_id');
    }
    public function taker_id() {
        return $this->belongsTo(User::class, 'taker_id');
    }
}
