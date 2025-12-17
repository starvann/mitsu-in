<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GroupController;
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
  Route::get('/dashboard/change-pass/{user}', [DashboardController::class, 'edit_password']);
  Route::put('/dashboard/change-pass/{user}', [DashboardController::class, 'update_password']);
  Route::get('/logout', [AuthController::class, 'logout']);
  # CRUD Exam
  Route::get('dashboard/create-exam', [DashboardController::class, 'create_exam']);
  Route::post('/dashboard/create-exam', [DashboardController::class, 'store_exam']);
  Route::get('/dashboard/manage-exam', [DashboardController::class, 'manage_exams']);
  Route::get('/dashboard/edit-exam/{exam}', [DashboardController::class, 'edit_exam']);
  Route::put('/dashboard/edit-exam/{exam}', [DashboardController::class, 'update_exam']);
  Route::delete('/dashboard/delete-exam/{exam}', [DashboardController::class, 'delete_exam']);
  Route::get('/dashboard/exam-result/{exam}', [DashboardController::class, 'exam_result']);
  Route::get('/dashboard/get-exam-results/{exam}', [DashboardController::class, 'get_exam_results']);
  Route::delete('/dashboard/del-exam-result/{exam_res}', [DashboardController::class, 'delete_exam_result']);
  Route::delete('/dashboard/del-all-exam-result/{exam}', [DashboardController::class, 'delete_all_exam_result']);
  # CRUD Users
  Route::get('/dashboard/students', [DashboardController::class, 'list_stdns']);
  Route::get('/dashboard/referrals', [DashboardController::class, 'list_refls']);
  Route::get('/dashboard/admins', [DashboardController::class, 'list_admns']);
  Route::get('/dashboard/get-users', [DashboardController::class, 'get_users']);
  Route::get('/dashboard/view-user/{user}', [DashboardController::class, 'view_user']);
  Route::delete('/dashboard/del-user/{user}', [DashboardController::class, 'delete_user']);
  Route::get('/dashboard/edit-user/{user}', [DashboardController::class, 'edit_user']);
  Route::put('/dashboard/edit-user/{user}', [DashboardController::class, 'update_user']);
  # Presence
  Route::post('/presence', [PresenceController::class, 'store_presence']);
  Route::get('/presence/percentage/{user}', [PresenceController::class, 'get_presence_data']);
  # Exam (for students)
  Route::get('/exam/{exam}', [ExamController::class, 'index']);
  Route::get('/exam-calc-result', [ExamController::class, 'calc_result']);
  Route::get('/exam-get-question', [ExamController::class, 'get_question']);
  Route::get('/exam-save-answer', [ExamController::class, 'save_answer']);
  # CRUD Group
  Route::get('/dashboard/groups', [GroupController::class, 'index']);
  Route::get('/dashboard/get-groups', [GroupController::class, 'get_groups']);
  Route::get('/dashboard/get-user-of-group/{group}', [GroupController::class, 'get_users_of_group']);
  Route::post('/dashboard/create-group', [GroupController::class, 'store']);
  Route::get('/dashboard/view-group/{group}', [GroupController::class, 'view']);
  Route::get('/dashboard/edit-group/{group}', [GroupController::class, 'edit']);
  Route::put('/dashboard/edit-group/{group}', [GroupController::class, 'update']);
  Route::delete('/dashboard/del-group/{group}', [GroupController::class, 'delete']);

});
Route::get('/presence', [PresenceController::class, 'store_presence']);
Route::get('/presence-qr/{id}', [PresenceController::class, 'generate_presence_qr']);