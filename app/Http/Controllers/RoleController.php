<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('status', 1)->get();
        return response()->json($roles, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', 
        ]);
    
        // Aynı isimde bir rol var mı ve statusu 0 mı kontrol et
        $existingRole = Role::where('name', $validated['name'])->where('status', 0)->first();
    
        if ($existingRole) {
            // Eğer var ise, statusu 1 yap ve eski rolü güncelle
            $existingRole->status = 1;
            $existingRole->save();
    
            return response()->json($existingRole, 200);
        }
    
        // Eğer böyle bir rol yoksa, yeni bir rol oluştur
        $role = Role::create(['name' => $validated['name'], 'status' => 1]);
    
        return response()->json($role, 201); 
    }
    public function show($id)
    {
        $role = Role::where('id', $id)->where('status', 1)->first();

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
            'name' => 'required|string|max:255',
        ]);

        $role->update(['name' => $validated['name']]);

        return response()->json($role, 200); 
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->update(['status' => 0]);

        return response()->json(['message' => 'Rol indi qeyri-aktivdir!'], 200); 
    }
}
