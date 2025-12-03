<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PresenceController;
use Illuminate\Support\Facades\Route;

# splash & onboarding
Route::get('/', function() {
    return view('splash');
});
Route::get('/onboarding', function() {
    return view('onboarding');
});
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
  Route::get('/logout', [AuthController::class, 'logout']);
  # CRUD Exam
  Route::get('dashboard/create-exam', [DashboardController::class, 'create_exam']);
  Route::post('/dashboard/create-exam', [DashboardController::class, 'store_exam']);
  Route::get('/dashboard/manage-exam', [DashboardController::class, 'manage_exams']);
  Route::get('/dashboard/edit-exam/{exam}', [DashboardController::class, 'edit_exam']);
  Route::put('/dashboard/edit-exam/{exam}', [DashboardController::class, 'update_exam']);
  Route::delete('/dashboard/delete-exam/{exam}', [DashboardController::class, 'delete_exam']);
  Route::get('/dashboard/exam-result/{exam}', [DashboardController::class, 'exam_result']);
  Route::get('/dashboard/del-exam-result/{exam_res}', [DashboardController::class, 'delete_exam_result']);
  Route::get('/dashboard/del-all-exam-result/{exam}', [DashboardController::class, 'delete_all_exam_result']);
  # RUD Students
  Route::get('/dashboard/students', [DashboardController::class, 'lists_user']);
  Route::get('/dashboard/view-user/{user}', [DashboardController::class, 'view_user']);
  Route::put('/dashboard/edit-user/{user}', [DashboardController::class, 'update_user']);
  # Presence
  Route::post('/presence', [PresenceController::class, 'store_presence']);
  Route::get('/presence/percentage/{user}', [PresenceController::class, 'gen_presence_percentage']);
  # Exam (for students)
  Route::get('/exam/{exam}', [ExamController::class, 'index']);
  Route::get('/exam-calc-result', [ExamController::class, 'calc_result']);
  Route::get('/exam-get-question', [ExamController::class, 'get_question']);
  Route::get('/exam-save-answer', [ExamController::class, 'save_answer']);
});
Route::get('/presence', [PresenceController::class, 'store_presence']);
Route::get('/presence-qr/{id}', [PresenceController::class, 'generate_presence_qr']);