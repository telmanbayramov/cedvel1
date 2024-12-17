<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;


class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name'];
    // Role -> Permissions ilişkisi (Bir role birden fazla izin)
    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    // }

    // // Role -> Users ilişkisi (Bir role birden fazla kullanıcı)
    // public function users()
    // {
    //     return $this->morphedByMany(User::class, 'model', 'model_has_roles', 'role_id', 'model_id');
    // }
}

