<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', [AuthController::class, 'home']);
# auth
Route::middleware('guest')->group(function()  {
    Route::get('/login', [AuthController::class, 'login']);
    Route::post('/login', [AuthController::class, 'try_login']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'create_user']);
    Route::get('/register2', [AuthController::class, 'register2']);
    Route::post('/register2', [AuthController::class, 'create_user2']);
});
# dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);