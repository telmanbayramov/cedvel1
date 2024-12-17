<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('status', 1)
                     ->with('permissions') 
                     ->get();
    
        return response()->json($roles, 200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string',
            'permissions' => 'required|array',
            'permissions.*' => 'string',
        ]);
        $role = Role::firstOrCreate(['name' => $validated['role_name']]);
        foreach ($validated['permissions'] as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();

            if ($permission) {
                $role->givePermissionTo($permission);
            } else {
                return response()->json([
                    'message' => "İcaze '{$permissionName}' tapilmadi."
                ], 404);
            }
        }
        return response()->json([
            'message' => 'Role created and permissions assigned successfully.',
            'role' => $role->load('permissions'),
        ], 201);
    }

    public function show($id)
    {
        $role = Role::where('id', $id)
                    ->where('status', 1)
                    ->with('permissions') 
                    ->first();
    
        if (!$role) {
            return response()->json(['error' => 'Belə bir rol tapılmadı!'], 404);
        }
    
        return response()->json($role, 200);
    }
    public function update(Request $request, $id)
    {
    $role = Role::findOrFail($id);
    if ($role->status == 0) {
        return response()->json(['error' => 'Bu rol qeyri-aktivdir və yenilənə bilməz.'], 400);
    }
    $validated = $request->validate([
        'role_name' => 'required|string',
        'permissions' => 'required|array',
        'permissions.*' => 'string',
    ]);
    $role->update(['name' => $validated['role_name']]);
    $role->permissions()->detach();
    foreach ($validated['permissions'] as $permissionName) {
        $permission = Permission::where('name', $permissionName)->first();

        if ($permission) {
            $role->givePermissionTo($permission);
        } else {
            return response()->json([
                'message' => "İcaze '{$permissionName}' tapılmadı."
            ], 404);
        }
    }
    return response()->json([
        'message' => 'Rol başarıyla güncellendi ve izinler atandı.',
        'role' => $role->load('permissions'),  
    ], 200);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->update(['status' => 0]);

        return response()->json(['message' => 'Rol indi qeyri-aktivdir!'], 200);
    }
}
