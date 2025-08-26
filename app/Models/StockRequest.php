<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    protected $fillable = [
        'seeker_branch_id', 'seeker_user_id', 'provider_branch_id', 'provider_user_id',
        'product_id', 'quantity', 'total_price', 'accepted_quantity', 'accepted_total_price',
        'is_accepted'
    ];

    public function seeker_branch() {
        return $this->belongsTo(Branch::class, 'seeker_branch_id');
    }
    public function seeker_user() {
        return $this->belongsTo(User::class, 'seeker_user_id');
    }
    public function provider_branch() {
        return $this->belongsTo(Branch::class, 'provider_branch_id');
    }
    public function provider_user() {
        return $this->belongsTo(User::class, 'provider_user_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
