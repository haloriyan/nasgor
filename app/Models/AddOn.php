<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    protected $fillable = [
        'branch_id', 'name', 'description', 'price',
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'product_add_ons', 'addon_id');
    }
}
