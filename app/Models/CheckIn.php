<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $fillable = [
        'user_id', 'branch_id',
        'coordinates', 'type', 'distance_from_branch', 'photo',
        'in_at', 'out_at', 'in_photo', 'out_photo', 'duration', 
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
