<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::middleware('jwt.auth')->group(function(){
    Route::get('roles',[RoleController::class,'index']);
Route::post('roles',[RoleController::class,'store']);
Route::get('roles/{id}',[RoleController::class,'show']);
Route::put('roles/{id}',[RoleController::class,'update']);
Route::delete('roles/{id}',[RoleController::class,'destroy']);
});

Route::middleware('jwt.auth')->group(function(){
    Route::get('permissions',[PermissionController::class,'index']);
    Route::get('permissions/{id}',[PermissionController::class,'show']);
    Route::post('permissions',[PermissionController::class,'store']);
    Route::put('permissions/{id}',[PermissionController::class,'update']);
    Route::delete('permissions/{id}',[PermissionController::class,'destroy']);    
});

Route::middleware('jwt.auth')->group(function(){
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}',[UserController::class,'show']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);
Route::post('users/{id}/assign-role', [UserController::class, 'assignRole']);
Route::post('users/{id}/give-permission', [UserController::class, 'givePermission']);
});
