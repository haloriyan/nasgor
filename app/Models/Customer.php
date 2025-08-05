<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'branch_id', 'name', 'email', 'phone', 'address', 'transaction_ability'
    ];

    public function types() {
        return $this->belongsToMany(CustomerType::class, 'customer_customer_types');
    }
}
