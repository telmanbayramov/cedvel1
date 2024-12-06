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
            return response()->json([
                'token' => $token,
            ]);
        }
        return response()->json(['error' => 'Login ve ya sifre yanlisdir'], 401);
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
                ]
            ]);
        }
        if ($userdata->status == 0) {
            return response()->json([
                "status" => false,
                "message" => "İstifadəçinin hesab yoxdur"
            ], 403);
        } else {
            return response()->json([
                "status" => false,
                "message" => "email ve ya parol sehvdir"
            ], 403);
        }
    }

    // public function user(Request $request)
    // {
    //     return response()->json($request->user());
    // }

    // public function logout(Request $request)
    // {
    //     JWTAuth::invalidate($request->token);

    //     return response()->json(['message' => 'Çıxış edildi']);
    // }
}
