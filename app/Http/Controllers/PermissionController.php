<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|unique:permissions,name']);
        $permission = Permission::create(['name' => $validated['name']]);
        return response()->json($permission, 201);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission, 200);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $validated = $request->validate(['name' => 'required|string|unique:permissions,name,' . $id]);
        $permission->update(['name' => $validated['name']]);
        return response()->json($permission, 200);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(['message' => 'Permission deleted'], 200);
    }
}
