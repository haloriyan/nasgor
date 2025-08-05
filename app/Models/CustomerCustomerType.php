<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCustomerType extends Model
{
    protected $fillable = [
        'customer_id', 'customer_type_id'
    ];

    public function type() {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
