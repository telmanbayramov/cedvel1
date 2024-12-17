<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{           // buna bax role repmission olmasin
    public function index()
    {
        $users = User::where('status', 1)->get();
    
        $usersWithDetails = $users->map(function($user) {
        
            return [
                "id" => $user->id,
                "name" => $user->name,
                "surname" => $user->surname,
                "patronymic" => $user->patronymic,
                "duty" => $user->duty,
                "employee_type" => $user->employee_type,
                "faculty_id" => $user->faculty_id,
                "department_id" => $user->department_id,
                "email" => $user->email,
            ];
        });
    
        return response()->json($usersWithDetails, 200);
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
                    "surname" => $userdata->surname,
                    "patronymic" => $userdata->patronymic,
                    "duty" => $userdata->duty,
                    "employee_type" => $userdata->employee_type,
                    "faculty_id" => $userdata->faculty_id,
                    "department_id" => $userdata->department_id,
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

    public function     store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'duty' => 'required|string|max:255',
            'employee_type' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => 'required|exists:departments,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'patronymic' => $validatedData['patronymic'],
            'duty' => $validatedData['duty'],
            'employee_type' => $validatedData['employee_type'],
            'faculty_id' => $validatedData['faculty_id'],
            'department_id' => $validatedData['department_id'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $role = Role::where('name', $validatedData['role'])->first();
        if ($role) {
            $user->assignRole($role); 
        }

        return response()->json(['message' => 'Kullanıcı başarıyla oluşturuldu!', 'user' => $user], 201);
    }
    

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        if ($user->status == 0) {
            return response()->json(['error' => 'Bu istifadəçi qeyri-aktivdir və güncellenemez'], 400);
        }
    
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'patronymic' => 'sometimes|string|max:255',
            'duty' => 'sometimes|string|max:255',
            'employee_type' => 'sometimes|string|max:255',
            'faculty_id' => 'sometimes|exists:faculties,id',
            'department_id' => 'sometimes|exists:departments,id',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'sometimes|string|exists:roles,name',
        ]);
            $user->update([
            'name' => $validatedData['name'] ?? $user->name,
            'surname' => $validatedData['surname'] ?? $user->surname,
            'patronymic' => $validatedData['patronymic'] ?? $user->patronymic,
            'duty' => $validatedData['duty'] ?? $user->duty,
            'employee_type' => $validatedData['employee_type'] ?? $user->employee_type,
            'faculty_id' => $validatedData['faculty_id'] ?? $user->faculty_id,
            'department_id' => $validatedData['department_id'] ?? $user->department_id,
            'email' => $validatedData['email'] ?? $user->email,
            'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $user->password,
        ]);
        if (isset($validatedData['role'])) {
            $role = Role::where('name', $validatedData['role'])->first();
            if ($role) {
                $user->syncRoles([$role]); 
            }
        }
        return response()->json([
            'message' => 'İstifadəçinin məlumatları uğurla dəyişdirildi!',
            'user' => $user
        ], 200);
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 0]);
        $user->roles()->update(['status' => 0]);
        return response()->json(['message' => 'İstifadəçi məlumatları silindi!'], 200);
    }
    

}
