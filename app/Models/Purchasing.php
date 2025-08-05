<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchasing extends Model
{
    protected $fillable = [
        'branch_id','supplier_id', 'inventory_id',
        'label', 'notes', 'total_quantity', 'total_price', 'status',
        'created_by', 'recipient', 'received_at'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function staff() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function receiver() {
        return $this->belongsTo(User::class, 'recipient');
    }
    public function supplier() {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function products() {
        return $this->belongsToMany(Product::class, 'purchasing_products');
    }
    public function items() {
        return $this->hasMany(PurchasingProduct::class, 'purchasing_id');
    }
}
