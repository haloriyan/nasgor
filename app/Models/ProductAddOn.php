<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAddOn extends Model
{
    protected $fillable = [
        'product_id', 'addon_id'
    ];

    public function addon() {
        return $this->belongsTo(AddOn::class, 'addon_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
