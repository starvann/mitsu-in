<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exam;
use App\Models\Presence;
use App\Models\Question;
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
      $token = PresenceController::get_presence_token(Auth::id());
      $exams = Exam::all();
      $status = Presence::select('status')->where('user_id', Auth::id())->whereDay('created_at', today())->first();
      
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
      'judul' => 'required|string|unique:exams,judul',
      'deskripsi' => 'required|string',
      'soal' => 'required|array|list',
      'soal.*.benar' => 'required|numeric|max_digits:2',
      'soal.*.soal' => 'required|string',
      'soal.*.jawaban' => 'required|array|list',
      'soal.*.jawaban.*' => 'required|string',
      'deadline' => 'nullable|datetime',
      'ready' => 'nullable|boolean',
    ]);
    $soals = $data['soal'];
    unset($data['soal']);
    $data['user_id'] = Auth::user()->id;
    $exam = Exam::create($data);
    foreach($soals as $soal) {
      Question::create([
        'exam_id' => $exam->id,
        'soal' => $soal['soal'],
        'jawaban' => $soal['jawaban'],
        'jwbn_yg_benar' => intval($soal['benar'])
      ]);
    }
    return redirect('/dashboard')->with('success', 'Ujian berhasil dibuat');
  }
  public function edit_exam(Exam $exam) {
    Gate::authorize('admin');
    return view('users.dashboard.exams.edit', ['exam' => $exam, 'questions' => $exam->questions]);
  }
  public function update_exam(Request $req, Exam $exam) {
    Gate::authorize('admin');
    $data = $req->validate([
      'judul' => 'required|string|unique:exams,judul',
      'deskripsi' => 'required|string',
      'soal' => 'required|array|list',
      'soal.*.benar' => 'required|numeric|max_digits:2',
      'soal.*.soal' => 'required|string',
      'soal.*.jawaban' => 'required|array|list',
      'soal.*.jawaban.*' => 'required|string',
      'deadline' => 'nullable|datetime',
      'ready' => 'nullable|boolean',
    ]);
    $soals = $data['soal'];
    unset($data['soal']);
    $exam->update($data);
    
  }
  public function delete_exam(Request $req, Exam $exam) {
    Gate::authorize('admin');
    $exam->questions()?->delete();
    $exam->delete();
    return redirect('/dashboard/manage-exam')->with('success', 'Ujian berhasil dihapus');
  }
}
