<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'branch_id', 'user_id', 'customer_id', 'movement_id',
        'invoice_number', 'total_quantity', 'total_price', 'payment_status',
        'notes', 'status',
    ];

    public function items() {
        return $this->hasMany(SalesItem::class, 'sales_id');
    }

    public function review() {
        return $this->hasOne(Review::class, 'sales_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
