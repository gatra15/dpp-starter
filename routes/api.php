<?php

use App\Models\Status;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UrusanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('public')->group(function () {
    Route::get('bookings/schedule', [BookingController::class, 'publicSchedule']);
});

Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::get('roles', [RoleController::class, 'index']);
    Route::post('roles', [RoleController::class, 'store']);
    Route::get('roles/{id}', [RoleController::class, 'show']);
    Route::put('roles/{id}', [RoleController::class, 'update']);
    Route::delete('roles/{id}', [RoleController::class, 'destroy']);

    Route::get('departments', [DepartmentController::class, 'index']);
    Route::post('departments', [DepartmentController::class, 'store']);
    Route::get('departments/{id}', [DepartmentController::class, 'show']);
    Route::put('departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('departments/{id}', [DepartmentController::class, 'destroy']);

    Route::get('urusan', [UrusanController::class, 'index']);
    Route::post('urusan', [UrusanController::class, 'store']);
    Route::get('urusan/{id}', [UrusanController::class, 'show']);
    Route::put('urusan/{id}', [UrusanController::class, 'update']);
    Route::delete('urusan/{id}', [UrusanController::class, 'destroy']);

    Route::get('facilities', [FacilityController::class, 'index']);
    Route::post('facilities', [FacilityController::class, 'store']);
    Route::get('facilities/options', [FacilityController::class, 'options']);
    Route::put('facilities/{id}', [FacilityController::class, 'update']);
    Route::delete('facilities/{id}', [FacilityController::class, 'destroy']);

    Route::get('rooms', [RoomController::class, 'index']);
    Route::post('rooms', [RoomController::class, 'store']);
    Route::get('rooms/{id}', [RoomController::class, 'show']);
    Route::put('rooms/{id}', [RoomController::class, 'update']);
    Route::delete('rooms/{id}', [RoomController::class, 'destroy']);

    Route::get('status', [StatusController::class, 'index']);
    Route::post('status', [StatusController::class, 'store']);
    Route::get('status/options', [StatusController::class, 'options']);
    Route::put('status/{id}', [StatusController::class, 'update']);
    Route::delete('status/{id}', [StatusController::class, 'destroy']);

    Route::get('bookings', [BookingController::class, 'index']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{id}', [BookingController::class, 'show']);
    Route::put('bookings/{id}', [BookingController::class, 'update']);
    Route::delete('bookings/{id}', [BookingController::class, 'destroy']);
    Route::post('bookings/{id}/approve', [BookingController::class, 'approve']);
    Route::post('bookings/{id}/reject', [BookingController::class, 'reject']);
});
