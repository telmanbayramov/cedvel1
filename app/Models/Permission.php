<?php
namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;


class Permission extends SpatiePermission
{
   

    protected $fillable = ['name', 'guard_name','status'];

    // Permission -> Roles ilişkisi (Bir izin birden fazla role ait olabilir)
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_has_permissions', 'permission_id', 'role_id');
    // }

    // // Permission -> Models ilişkisi (Bir izin birden fazla modele atanabilir)
    // public function models()
    // {
    //     return $this->morphedByMany(User::class, 'model', 'model_has_permissions', 'permission_id', 'model_id');
    // }
}
