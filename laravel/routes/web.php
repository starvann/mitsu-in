<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

# auth
Route::get('/', [AuthController::class, 'home']);
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'try_login']);
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'create_user']);
Route::get('/register2', [AuthController::class, 'register2']);
Route::post('/register2', [AuthController::class, 'create_user2']);
