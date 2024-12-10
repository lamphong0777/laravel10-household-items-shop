<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['user_id','name', 'email', 'phone', 'position', 'address'];

    // app/Models/Staff.php
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'staff_permissions', 'staff_id', 'permission_id');
    }
    public function hasPermission($permissionSlug)
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
