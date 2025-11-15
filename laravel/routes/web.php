<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'home']);
Route::get('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'login']);
