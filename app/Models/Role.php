<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'description', 'multibranch'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }

    public function accesses() {
        return $this->hasMany(UserAccess::class, 'role_id');
    }
    public function users() {
        return $this->belongsToMany(User::class, 'user_accesses');
    }
}
