<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    protected $fillable = [
        'name', 'color', 'branch_id'
    ];

    public function customers() {
        return $this->belongsToMany(Customer::class, 'customer_customer_types');
    }
}
