<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'sales_id', 'customer_id', 'branch_id',
        'rating', 'body'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function order() {
        return $this->belongsTo(Sales::class, 'sales_id');
    }
}
