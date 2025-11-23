<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exam;
use App\Models\Presence;
use App\Http\Controllers\PresenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DashboardController extends Controller
{
  public function index() {
    $role = Auth::user()->role;
    if($role == 'admn') {
      return view('users.dashboard.admn');
    }
    elseif($role == 'refl') {
      $refUsers = User::where('kode_ref', Auth::user()->kode_ref_saya)->get();
      return view('users.dashboard.refl', ['refUsers' => $refUsers]);
    }
    else {
      $token = PresenceController::get_presence_token(Auth::user()->id);
      $exams = Exam::all();
      $status = Presence::select('status')->where('user_id', auth()->user()->id)->whereDay('created_at', today())->first();
      
      return view('users.dashboard.stdn', [
        'token' => $token, 'exams' => $exams, 
        'user' => Auth::user(), 'hasPresence' => boolval($status),
        'status' => is_null($status) ? null : $status['status']
      ]);
    }
  }
  public function show_exams() {
    Gate::authorize('admin');
    return view('users.dashboard.exams.view', ['exams' => Exam::all()]);
  }
  public function create_exam() {
    Gate::authorize('admin');
    return view('users.dashboard.exams.create');
  }
  public function store_exam(Request $req) {
    Gate::authorize('admin');
    $data = $req->validate([
      'nama' => 'required|string',
      'deskripsi' => 'required|string',
      'deadline' => 'nullable|datetime',
      'draft' => 'required|boolean',
    ]);
    $data['user_id'] = Auth::user()->id;
    Exam::create($data);
    return redirect('/dashboard')->with('success', 'Ujian berhasil dibuat');
  }
  public function edit_exam(Request $req, Exam $exam) {
    Gate::authorize('admin');
    return view('users.dashboard.exams.edit', ['exam' => $exam, 'questions' => $exam->questions]);
  }
  public function update_exam(Request $req, Exam $exam) {
    
  }
  
}
