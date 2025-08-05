<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesItemAddon extends Model
{
    protected $fillable = [
        'item_id', 'addon_id', 'price', 'quantity', 'total_price'
    ];

    public function addon() {
        return $this->belongsTo(AddOn::class, 'addon_id');
    }
}
