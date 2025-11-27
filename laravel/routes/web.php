<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresenceController;

Route::get('/', [AuthController::class, 'home']);
# auth
Route::middleware('guest')->group(function()  {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'try_login']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'create_user']);
    Route::get('/register2', [AuthController::class, 'register2']);
    Route::post('/register2', [AuthController::class, 'create_user2']);
});
Route::middleware('auth')->group(function() {
  # dashboard
  Route::get('/dashboard', [DashboardController::class, 'index']);
  Route::get('dashboard/create-exam', [DashboardController::class, 'create_exam']);
  Route::post('/dashboard/create-exam', [DashboardController::class, 'store_exam']);
  Route::get('/dashboard/manage-exam', [DashboardController::class, 'manage_exams']);
  Route::get('/dashboard/edit-exam/{exam}', [DashboardController::class, 'edit_exam']);
  Route::put('/dashboard/edit-exam/{exam}', [DashboardController::class, 'update_exam']);
  Route::delete('/dashboard/delete-exam/{exam}', [DashboardController::class, 'delete_exam']);
  Route::get('/logout', [AuthController::class, 'logout']);
  Route::get('/dashboard/students', [DashboardController::class, 'lists_user']);
  Route::get('/dashboard/view-user/{user}', [DashboardController::class, 'view_user']);
  Route::put('/dashboard/edit-user/{user}', [DashboardController::class, 'update_user']);
  Route::post('/presence', [PresenceController::class, 'store_presence']);
});
Route::get('/presence', [PresenceController::class, 'store_presence']);
Route::get('/presence-qr/{id}', [PresenceController::class, 'generate_presence_qr']);