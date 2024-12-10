<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('status',1)->get();
        return response()->json($permissions, 200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string']);
         $existingPermission = Permission::where('name', $validated['name'])->first();
        if ($existingPermission) {
            if ($existingPermission->status == 0) {
                $existingPermission->status = 1;
                $existingPermission->save();
    
                return response()->json([
                    'message' => 'İcazə deaktiv edilib.',
                    'permission' => $existingPermission
                ], 200);
            }
                return response()->json([
                'message' => 'İcazə artıq mövcuddur və aktivdir.'
            ], 400);
        }
    
        $permission = Permission::create([
            'name' => $validated['name'],
            'status' => 1
        ]);
    
        return response()->json([
            'message' => 'İcazə uğurla yaradıldı.',
            'permission' => $permission
        ], 201);
    }
    public function show($id)
    {
        $permission = Permission::where('id', $id)->where('status', 1)->firstOrFail();
        return response()->json($permission, 200);
    }
    public function update(Request $request, $id)
    {
        $permission = Permission::where('id', $id)->where('status', 1)->firstOrFail();
        $validated = $request->validate(['name' => 'required|string|unique:permissions,name,' . $id]);
        $permission->update(['name' => $validated['name']]);
        return response()->json($permission, 200);
    }
    
    public function destroy($id)
    {
        $permission = Permission::where('id', $id)->where('status', 1)->firstOrFail();
        $permission->update(['status' => 0]);
        return response()->json(['message' => 'İcazə deaktiv edilib'], 200);
    }
}
