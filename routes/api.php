<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GroupController;
use App\Models\Group;

Route::post('login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
});
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
// Route::middleware('jwt.auth')->group(function(){
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}',[UserController::class,'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
// });
Route::middleware('jwt.auth')->group(function(){
    Route::get('/faculty',[FacultyController::class,'index']);
    Route::get('/faculty/{id}',[FacultyController::class,'show']);
    Route::post('/faculty',[FacultyController::class,'create']);
    Route::put('/faculty/{id}',[FacultyController::class,'update']);
    Route::delete('/faculty/{id}',[FacultyController::class,'delete']);
});
Route::middleware('jwt.auth')->group(function(){
    Route::get('/department',[DepartmentController::class,'index']);
    Route::get('/department/{id}',[DepartmentController::class,'show']);
    Route::post('/department',[DepartmentController::class,'create']);
    Route::put('/department/{id}',[DepartmentController::class,'update']);
    Route::delete('/department/{id}',[DepartmentController::class,'delete']);
});
Route::middleware('jwt.auth')->group(function(){
    Route::get('/speciality',[SpecialityController::class,'index']);
    Route::get('/speciality/{id}',[SpecialityController::class,'show']);
    Route::post('/speciality',[SpecialityController::class,'create']);
    Route::put('/speciality/{id}',[SpecialityController::class,'update']);
    Route::delete('/speciality/{id}',[SpecialityController::class,'delete']);
});
Route::middleware('jwt.auth')->group(function(){
    Route::get('/course',[CourseController::class,'index']);
    Route::get('/course/{id}',[CourseController::class,'show']);
    Route::post('/course',[CourseController::class,'store']);
    Route::put('/course/{id}',[CourseController::class,'update']);
    Route::delete('/course/{id}',[CourseController::class,'destroy']);
});
Route::middleware('jwt.auth')->group(function(){
    Route::get('group',[GroupController::class,'index']);
    Route::get('group/{id}',[GroupController::class,'show']);
    Route::post('group',[GroupController::class,'store']);
    Route::put('group/{id}',[GroupController::class,'update']);
    Route::delete('group/{id}',[GroupController::class,'destroy']);
});