<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBranch extends Model
{
    protected $fillable = [
        'user_id', 'branch_id', 'role_id'
    ];

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
