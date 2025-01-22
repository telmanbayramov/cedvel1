<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CorpsController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\DisciplinController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HourController;
use App\Http\Controllers\LessonTypeController;
use App\Http\Controllers\PendingScheduleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\WeekTypeController;
use App\Models\Day;
use App\Models\Group;
use App\Models\LessonType;
use App\Models\Schedule;

Route::post('login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('roles', [RoleController::class, 'store']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::put('roles/{id}', [RoleController::class, 'update']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('permissions', [PermissionController::class, 'index']);
    Route::get('permissions/{id}', [PermissionController::class, 'show']);
    Route::post('permissions', [PermissionController::class, 'store']);
    Route::put('permissions/{id}', [PermissionController::class, 'update']);
    Route::delete('permissions/{id}', [PermissionController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('/faculties', [FacultyController::class, 'index']);
    Route::get('/faculties/{id}', [FacultyController::class, 'show']);
    Route::post('/faculties', [FacultyController::class, 'create']);
    Route::put('/faculties/{id}', [FacultyController::class, 'update']);
    Route::delete('/faculties/{id}', [FacultyController::class, 'delete']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::post('/departments', [DepartmentController::class, 'create']);
    Route::put('/departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'delete']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('/specialities', [SpecialityController::class, 'index']);
    Route::get('/specialities/{id}', [SpecialityController::class, 'show']);
    Route::post('/specialities', [SpecialityController::class, 'create']);
    Route::put('/specialities/{id}', [SpecialityController::class, 'update']);
    Route::delete('/specialities/{id}', [SpecialityController::class, 'delete']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('groups', [GroupController::class, 'index']);
    Route::get('groups/{id}', [GroupController::class, 'show']);
    Route::post('groups', [GroupController::class, 'store']);
    Route::put('groups/{id}', [GroupController::class, 'update']);
    Route::delete('groups/{id}', [GroupController::class, 'destroy']);
    Route::get('/groups-info', [GroupController::class, 'getGroupInfo']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('lessontypes', [LessonTypeController::class, 'index']);
    Route::get('lessontypes/{id}', [LessonTypeController::class, 'show']);
    Route::post('lessontypes', [LessonTypeController::class, 'store']);
    Route::put('lessontypes/{id}', [LessonTypeController::class, 'update']);
    Route::delete('lessontypes/{id}', [LessonTypeController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('corps', [CorpsController::class, 'index']);
    Route::get('corps/{id}', [CorpsController::class, 'show']);
    Route::post('corps', [CorpsController::class, 'store']);
    Route::put('corps/{id}', [CorpsController::class, 'update']);
    Route::delete('corps/{id}', [CorpsController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('roomtypes', [RoomTypeController::class, 'index']);
    Route::get('roomtypes/{id}', [RoomTypeController::class, 'show']);
    Route::post('roomtypes', [RoomTypeController::class, 'store']);
    Route::put('roomtypes/{id}', [RoomTypeController::class, 'update']);
    Route::delete('roomtypes/{id}', [RoomTypeController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('rooms', [RoomController::class, 'index']);
    Route::get('rooms/{id}', [RoomController::class, 'show']);
    Route::post('rooms', [RoomController::class, 'store']);
    Route::put('rooms/{id}', [RoomController::class, 'update']);
    Route::delete('rooms/{id}', [RoomController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('days', [DayController::class, 'index']);
    Route::get('days/{id}', [DayController::class, 'show']);
    Route::post('days', [DayController::class, 'store']);
    Route::put('days/{id}', [DayController::class, 'update']);
    Route::delete('days/{id}', [DayController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('hours', [HourController::class, 'index']);
    Route::get('hours/{id}', [HourController::class, 'show']);
    Route::post('hours', [HourController::class, 'store']);
    Route::put('hours/{id}', [HourController::class, 'update']);
    Route::delete('hours/{id}', [HourController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('lessons', [DisciplinController::class, 'index']);
    Route::get('lessons/{id}', [DisciplinController::class, 'show']);
    Route::post('lessons', [DisciplinController::class, 'store']);
    Route::put('lessons/{id}', [DisciplinController::class, 'update']);
    Route::delete('lessons/{id}', [DisciplinController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('semesters', [SemesterController::class, 'index']);
    Route::get('semesters/{id}', [SemesterController::class, 'show']);
    Route::post('semesters', [SemesterController::class, 'store']);
    Route::put('semesters/{id}', [SemesterController::class, 'update']);
    Route::delete('semesters/{id}', [SemesterController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('weektypes', [WeekTypeController::class, 'index']);
    Route::get('weektypes/{id}', [WeekTypeController::class, 'show']);
    Route::post('weektypes', [WeekTypeController::class, 'store']);
    Route::put('weektypes/{id}', [WeekTypeController::class, 'update']);
    Route::delete('weektypes/{id}', [WeekTypeController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('schedules', [ScheduleController::class, 'index']);
    Route::get('schedules/{id}', [ScheduleController::class, 'show']);
    Route::post('schedules', [ScheduleController::class, 'store']);
    Route::put('schedules/{id}', [ScheduleController::class, 'update']);
    Route::delete('schedules/{id}', [ScheduleController::class, 'destroy']);
    Route::get('departmentsbyfaculty/{faculty_id}', [ScheduleController::class, 'getDepartmentsByFaculty']);
    Route::get('groupsbyfaculty/{faculty_id}', [ScheduleController::class, 'getGroupsByFaculty']);
    Route::get('disciplinesbydepartment/{department_id}', [ScheduleController::class, 'getDisciplinesByDepartment']);
    Route::get('userbydepartment/{department_id}', [ScheduleController::class, 'getUsersByDepartment']);
    Route::get('/faculty-schedules', [ScheduleController::class, 'facultySchedules']);
    Route::get('/pending-schedules', [PendingScheduleController::class, 'pendingSchedules']); 
    Route::put('/pending-schedules/{id}', [PendingScheduleController::class, 'approve']);
});
