<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'user_id', 'supplier_id', 'branch_id', 'purchasing_id', 'sales_id', 'movement_id_ref', 'branch_id_destination',
        'label', 'type', 'notes', 'status', 'total_quantity', 'total_price'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function branch_destination() {
        return $this->belongsTo(Branch::class, 'branch_id_destination');
    }
    public function origin() {
        return $this->belongsTo(StockMovement::class, 'movement_id_ref');
    }
    public function supplier() {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'stock_movement_products');
    }
    public function items() {
        return $this->hasMany(StockMovementProduct::class, 'movement_id');
    }
}
