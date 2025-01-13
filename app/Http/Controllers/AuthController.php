<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
      if ($token = JWTAuth::attempt($credentials, ['exp' => now()->addHour()])) {
            $user = JWTAuth::user();
    
            if ($user->status == 1) {
                return response()->json([
                    'status' => 1,
                    'token' => $token,
                ]);
            }
        }
        return response()->json([
            'status' => 0,
            'error' => 'Login veya şifre yanlıştır.',
        ], 401);
    }
    
    public function profile()
    {
        $userdata = auth()->user();
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
                ],
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "İstifadəçinin hesabı yoxdur",
            ], 403); 
        }
    }
    
}
