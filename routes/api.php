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
 Route::middleware('jwt.auth')->group(function(){
Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);
 });
Route::middleware('jwt.auth')->group(function () {
    Route::get('/faculty', [FacultyController::class, 'index']);
    Route::get('/faculty/{id}', [FacultyController::class, 'show']);
    Route::post('/faculty', [FacultyController::class, 'create']);
    Route::put('/faculty/{id}', [FacultyController::class, 'update']);
    Route::delete('/faculty/{id}', [FacultyController::class, 'delete']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('/department', [DepartmentController::class, 'index']);
    Route::get('/department/{id}', [DepartmentController::class, 'show']);
    Route::post('/department', [DepartmentController::class, 'create']);
    Route::put('/department/{id}', [DepartmentController::class, 'update']);
    Route::delete('/department/{id}', [DepartmentController::class, 'delete']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('/speciality', [SpecialityController::class, 'index']);
    Route::get('/speciality/{id}', [SpecialityController::class, 'show']);
    Route::post('/speciality', [SpecialityController::class, 'create']);
    Route::put('/speciality/{id}', [SpecialityController::class, 'update']);
    Route::delete('/speciality/{id}', [SpecialityController::class, 'delete']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('/course', [CourseController::class, 'index']);
    Route::get('/course/{id}', [CourseController::class, 'show']);
    Route::post('/course', [CourseController::class, 'store']);
    Route::put('/course/{id}', [CourseController::class, 'update']);
    Route::delete('/course/{id}', [CourseController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('group', [GroupController::class, 'index']);
    Route::get('group/{id}', [GroupController::class, 'show']);
    Route::post('group', [GroupController::class, 'store']);
    Route::put('group/{id}', [GroupController::class, 'update']);
    Route::delete('group/{id}', [GroupController::class, 'destroy']);
    Route::get('/group-info', [GroupController::class, 'getGroupInfo']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('lessontype', [LessonTypeController::class, 'index']);
    Route::get('lessontype/{id}', [LessonTypeController::class, 'show']);
    Route::post('lessontype', [LessonTypeController::class, 'store']);
    Route::put('lessontype/{id}', [LessonTypeController::class, 'update']);
    Route::delete('lessontype/{id}', [LessonTypeController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('corps', [CorpsController::class, 'index']);
    Route::get('corps/{id}', [CorpsController::class, 'show']);
    Route::post('corps', [CorpsController::class, 'store']);
    Route::put('corps/{id}', [CorpsController::class, 'update']);
    Route::delete('corps/{id}', [CorpsController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('roomtype', [RoomTypeController::class, 'index']);
    Route::get('roomtype/{id}', [RoomTypeController::class, 'show']);
    Route::post('roomtype', [RoomTypeController::class, 'store']);
    Route::put('roomtype/{id}', [RoomTypeController::class, 'update']);
    Route::delete('roomtype/{id}', [RoomTypeController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('room', [RoomController::class, 'index']);
    Route::get('room/{id}', [RoomController::class, 'show']);
    Route::post('room', [RoomController::class, 'store']);
    Route::put('room/{id}', [RoomController::class, 'update']);
    Route::delete('room/{id}', [RoomController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('day', [DayController::class, 'index']);
    Route::get('day/{id}', [DayController::class, 'show']);
    Route::post('day', [DayController::class, 'store']);
    Route::put('day/{id}', [DayController::class, 'update']);
    Route::delete('day/{id}', [DayController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('hour', [HourController::class, 'index']);
    Route::get('hour/{id}', [HourController::class, 'show']);
    Route::post('hour', [HourController::class, 'store']);
    Route::put('hour/{id}', [HourController::class, 'update']);
    Route::delete('hour/{id}', [HourController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('lesson', [DisciplinController::class, 'index']);
    Route::get('lesson/{id}', [DisciplinController::class, 'show']);
    Route::post('lesson', [DisciplinController::class, 'store']);
    Route::put('lesson/{id}', [DisciplinController::class, 'update']);
    Route::delete('lesson/{id}', [DisciplinController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('semestr', [SemesterController::class, 'index']);
    Route::get('semestr/{id}', [SemesterController::class, 'show']);
    Route::post('semestr', [SemesterController::class, 'store']);
    Route::put('semestr/{id}', [SemesterController::class, 'update']);
    Route::delete('semestr/{id}', [SemesterController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('weektype', [WeekTypeController::class, 'index']);
    Route::get('weektype/{id}', [WeekTypeController::class, 'show']);
    Route::post('weektype', [WeekTypeController::class, 'store']);
    Route::put('weektype/{id}', [WeekTypeController::class, 'update']);
    Route::delete('weektype/{id}', [WeekTypeController::class, 'destroy']);
});
Route::middleware('jwt.auth')->group(function () {
    Route::get('schedule', [ScheduleController::class, 'index']);
    Route::get('schedule/{id}', [ScheduleController::class, 'show']);
    Route::post('schedule', [ScheduleController::class, 'store']);
    Route::put('schedule/{id}', [ScheduleController::class, 'update']);
    Route::delete('schedule/{id}', [ScheduleController::class, 'destroy']);
    Route::get('departmentsbyfaculty/{faculty_id}', [ScheduleController::class, 'getDepartmentsByFaculty']);
    Route::get('groupsbyfaculty/{faculty_id}', [ScheduleController::class, 'getGroupsByFaculty']);
    Route::get('disciplinesbydepartment/{department_id}', [ScheduleController::class, 'getDisciplinesByDepartment']);
    Route::get('userbydepartment/{department_id}', [ScheduleController::class, 'getUsersByDepartment']);
    Route::get('/faculty-schedules', [ScheduleController::class, 'facultySchedules']);

});



