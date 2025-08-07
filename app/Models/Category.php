<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'image', 'is_active', 'pos_visibility', 'priority'
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'product_categories')
        ->orderBy('priority', 'DESC')
        ->orderBy('created_at', 'DESC');
    }
}
