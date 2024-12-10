<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('status', 1)->get(); 
        return response()->json($users, 200);
    }

    public function show($id)
    {
        $userdata = User::findOrFail($id);
        $roles = $userdata->roles->pluck('name');
        $permissions = $userdata->getAllPermissions()->pluck('name');
        if ($userdata->status != 0) {
            return response()->json([
                "status" => true,
                "message" => "Profile data",
                "userData" => [
                    "id" => $userdata->id,
                    "name" => $userdata->name,
                    "email" => $userdata->email,
                    "roles" => $roles,
                    "permissions" => $permissions,
                ]
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "İstifadəçinin hesab yoxdur"
        ], 403);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $role = Role::where('name', $validatedData['role'])->first();
        if ($role) {
            $user->assignRole($role);
        }

        return response()->json(['message' => 'İstifadəçi uğurla əlavə edildi!', 'user' => $user], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        if ($user->status == 0) {
            return response()->json(['error' => 'Bu istifadəçi qeyri-aktivdir ve güncellene bilmez'], 400);
        }
    
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6', 
            'role' => 'sometimes|string|exists:roles,name',  
        ]);
    
        $user->update([
            'name' => $validatedData['name'] ?? $user->name,
            'email' => $validatedData['email'] ?? $user->email,
            'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $user->password,
        ]);
    
        if (isset($validatedData['role'])) {
            $role = Role::where('name', $validatedData['role'])->first();
            if ($role) {
                $user->syncRoles([$role]);
            }
        }
    
        return response()->json(['message' => 'İstifadəçinin məlumatları uğurla dəyişdirildi!', 'user' => $user], 200);
    }
    
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->update(['status' => 0]);

        return response()->json(['message' => 'İstifadəçi məlumatları silindi!'], 200);
    }

    public function assignRole(Request $request, $id)
    {
        $validatedData = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);

        $role = Role::where('name', $validatedData['role'])->first();
        if ($role) {
            $user->assignRole($role);
        }

        return response()->json(['message' => 'Rol uğurla təyin edildi!'], 200);
    }

    public function givePermission(Request $request, $id)
    {
        $validatedData = $request->validate([
            'permission' => 'required|string|exists:permissions,name',
        ]);

        $user = User::findOrFail($id);
        $user->givePermissionTo($validatedData['permission']);

        return response()->json(['message' => 'İcazə uğurla verildi!'], 200);
    }
}
