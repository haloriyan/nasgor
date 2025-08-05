<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'pic_name', 'email', 'phone', 'address', 'notes', 'photo'
    ];
}
