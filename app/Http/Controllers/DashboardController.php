<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exam;
use App\Models\Presence;
use App\Models\Question;
use App\Http\Controllers\PresenceController;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;

class DashboardController extends Controller
{
  public function index(Request $req) {
    $role = Auth::user()->role;
    if($role == 'admn') {
      if(Auth::user()->stat == 'pending') {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect('/login')->withErrors(['Admin tidak terverifikasi!']);
      }
      return view('users.dashboard.admn');
    }
    elseif($role == 'refl') {
      $refUsers = User::select(['nama', 'gmb_profil', 'stat'])->where('kode_ref', Auth::user()->kode_ref_saya)->get();
      return view('users.dashboard.refl', ['refUsers' => $refUsers, 'user' => Auth::user()]);
    }
    else {
      $token = PresenceController::get_presence_token(Auth::id());
      $exams = Exam::where('siap_rilis', true)->get();
      $status = Presence::select('status')->where('user_id', Auth::id())->whereDay('created_at', today())->first();
      
      return view('users.dashboard.stdn', [
        'token' => $token, 'exams' => $exams, 
        'user' => Auth::user(), 'hasPresence' => boolval($status),
        'status' => is_null($status) ? null : $status['status']
      ]);
    }
  }
  // users
  public function list_stdns() {
    Gate::authorize('admin');
    return view('users.dashboard.users.list-stdn', $this->get_user_count());
  }
  public function list_refls() {
    Gate::authorize('admin');
    return view('users.dashboard.users.list-refl', $this->get_user_count('refl'));
  }
  public function list_admns() {
    Gate::authorize('admin');
    return view('users.dashboard.users.list-admn', $this->get_user_count('admn'));
  }
  private function get_user_count($role = 'stdn') {
    return ['dataCount' => User::where('role', $role)->count()];
  }
  public function get_users(Request $req) {
    Gate::authorize('admin');
    $role = $req->input('type', 'stdn');
    if(!in_array($role, ['stdn', 'refl', 'admn', 'no-admn'])) return response([], 400);
    if($role == 'no-admn') $users = User::select('id', 'nama', 'email', 'stat', 'gmb_profil')->where('role', '!=', 'admn');
    else $users = User::select('id', 'nama', 'email', 'stat', 'gmb_profil')->where('role', $role);
    if($req->has('q')) {
      $keyword = $req->input('q');
      if(!is_string($keyword)) {
        return response([], 400);
      }
      if(strlen($keyword) < 3) {
        return response([], 400);
      }
      $users = $users->where('nama', 'like', '%'.$keyword.'%')->orWhere('email', 'like', '%'.$keyword.'%');
    }
    $users = $users->limit(10)->get();
    return response()->json($users);
  }
  public function edit_user(User $user) {
    Gate::authorize('admin');
    $data = ['user' => $user];
    return view('users.dashboard.users.edit', $data);
  }
  public function view_user(User $user) {
    Gate::authorize('admin');
    $data = ['user' => $user];
    if($user->kode_ref != null) {
      $data['referrer_id'] = User::where('kode_ref_saya', $user->kode_ref)->first()->id;
    } else if($user->role == 'refl') {
      $data['ref_users_count'] = User::where('kode_ref', $user->kode_ref_saya)->count();
    }
    return view('users.dashboard.users.view', $data);
  }
  public function delete_user(User $user) {
    Gate::authorize('admin');
    $files = $user->presences()->pluck('doc_xtra');
    foreach($files as $file) {
      if($file) Storage::delete($file);
    }
    $user->delete();
    Storage::delete($user->gmb_profil);
    return redirect('/dashboard/students')->with('success', 'User telah dihapus.');
  }
  public function update_user(Request $req, User $user) {
    Gate::authorize('admin');
    if($req->has('stat_only')) {
      $data = $req->validate([
        'stat' => ['required', Rule::in(['pending', 'accepted'])]
      ]);
      $user->update($data);
      return redirect("dashboard/view-user/$user->id")->with('success', 'Berhasil update!');
    }
    $rules = [];
    $is_any_field_filled = !empty($req->input('relasi_di_jepang.nama')) or !empty($req->input('relasi_di_jepang.relasi'))
      or !empty($req->input('relasi_di_jepang.umur')) or !empty($req->input('relasi_di_jepang.pekerjaan'))
      or !empty($req->input('relasi_di_jepang.alamat'));
    if($is_any_field_filled) {
      $rules = [
        'relasi_di_jepang' => 'required|array',
        'relasi_di_jepang.nama' => 'required|string|min:3',
        'relasi_di_jepang.relasi' => 'required|string|min:3',
        'relasi_di_jepang.pekerjaan' => 'required|string|min:3',
        'relasi_di_jepang.usia' => 'required|numeric|integer|max_digits:3',
        'relasi_di_jepang.alamat' => 'required|string|min:3',
      ];
    }
    $rules = $rules + [
      'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
      'role' => ['required', 'string', Rule::in(['stdn', 'refl', 'admn'])],
      'kode_ref' => 'nullable|string|max:8|exists:users,kode_ref_saya',
      'nama' => 'required|string|min:3',
      'no_hp' => 'required|string|digits_between:9,16',
      'gmb_profil' => 'nullable|file|image|max:2048',
      'gender' => ['required', Rule::in(['laki-laki', 'perempuan'])],
      'umur' => 'required|numeric|integer|min:1',
      'tinggi_badan' => 'required|numeric|integer|min:1',
      'berat_badan' => 'required|numeric|integer|min:1',
      'status_pernikahan' => 'required|string',
      'gol_darah' => 'required|max:2|alpha:ascii|uppercase',
      'agama' => 'required|string|max:32',
      'pernah_ke_jepang' => 'required|boolean',
      'punya_paspor' => 'required|boolean',
      'tangan_utama' => ['required', Rule::in(['kanan', 'kiri', 'keduanya'])],
      'alamat' => 'required|string|min:9|max:512',
      'pendidikan' => 'required|array',
      'pendidikan.*.tahun' => 'required|numeric|integer|digits:4',
      'pendidikan.*.nama_sekolah' => 'required|string|min:4',
      'pendidikan.*.jurusan' => 'required|string',
      'pengalaman' => 'nullable|array|list',
      'struktur_keluarga' => 'required|array',
      'struktur_keluarga.*.relasi' => 'required|string|min:3',
      'struktur_keluarga.*.nama' => 'required|string|min:3',
      'struktur_keluarga.*.umur' => 'required|numeric|integer|max_digits:3',
      'struktur_keluarga.*.pekerjaan' => 'required|string|min:3',
      'struktur_keluarga.*.gaji' => 'required|string',
      'tujuan_ke_jepang' => 'required|max:256',
      'tujuan_stlh_kembali' => 'required|max:256',
      'kelebihan' => 'required|max:256',
      'kekurangan' => 'required|max:256',
      'hobi' => 'required|max:256',
      'sertif_jlpt' => 'nullable|string',
      'punya_sim_a' => 'required|boolean',
      'sertif_lain' => 'nullable|max:256',
      'relasi_di_jepang' => 'nullable',
      'catatan_xtra' => 'nullable|max:512',
    ];
    $data = $req->validate($rules);
    if(!$is_any_field_filled) {
      $data['relasi_di_jepang'] = [];
    }
    // upload profile picture if exists
    if($req->hasFile('gmb_profil')) {
      Storage::delete($user->gmb_profil);
      $data['gmb_profil'] = $req->file('gmb_profil')->store('assets/profiles');
    }
    else unset($data['gmb_profil']);
    $data['status_pernikahan'] = str($data['status_pernikahan'])->ucfirst();
    $user->update($data);
    return redirect('/dashboard/view-user/'.$user->id)->with('success', 'Berhasil update!');
  }
  // exams
  public function manage_exams() {
    Gate::authorize('admin');
    return view('users.dashboard.exams.manage', ['exams' => Exam::all()]);
  }
  public function exam_result(Exam $exam) {
    Gate::authorize('admin');
    return view('users.dashboard.exams.result', ['exam' => $exam, 'results' => $exam->examResults()->with('user')->get()]);
  }
  public function get_exam_results(Request $req, Exam $exam) {
    Gate::authorize('admin');
    $users = [];
    $datas = [];
    if($req->query('q')) {
      $keyword = $req->query('q');
      if(!is_string($keyword)) {
        return response()->json([], 400);
      }
      if(strlen($keyword) < 3) {
        return response()->json([], 400);
      }
        $users = User::where('nama', 'like', '%'.$keyword.'%')->orWhere('email', 'like', '%'.$keyword.'%')->limit(10)->pluck('id');
        $datas = $exam->examResults()->whereIn('user_id', $users);
      } else {
        $datas = $exam->examResults()->limit(10);
      }
      $datas = $datas->with('user:id,nama,email')->get();
      $datas = $datas->map(function($item, $key) {
        return [
          'id' => $item->id,
          'nama' => $item->user->nama,
          'email' => $item->user->email,
          'nilai' => $item->nilai,
          'total_benar' => $item->total_benar,
          'total_salah' => $item->total_salah
        ];
      });
      return response()->json($datas);
  }
  public function delete_exam_result(ExamResult $exam_res) {
    Gate::authorize('admin');
    $exam_res->delete();
    return redirect('/dashboard/exam-result/'.$exam_res->exam_id)->with('success', 'Hasil ujian telah dihapus.');
  }
  public function delete_all_exam_result(Exam $exam) {
    Gate::authorize('admin');
    $exam->examResults()->delete();
    return redirect('/dashboard/exam-result/'.$exam->id)->with('success', 'Semua hasil ujian telah dihapus.');
  }
  public function create_exam() {
    Gate::authorize('admin');
    return view('users.dashboard.exams.create');
  }
  public function store_exam(Request $req) {
    Gate::authorize('admin');
    //dd($req->input());
    $data = $req->validate([
      'judul' => 'required|string|unique:exams,judul',
      'deskripsi' => 'required|string',
      'soal' => 'required|array|list',
      'soal.*.jwbn_yg_benar' => 'required|numeric|max_digits:2',
      'soal.*.soal' => 'required|string',
      'soal.*.jawaban' => 'required|array|list',
      'soal.*.jawaban.*' => 'required|string',
      'deadline' => 'nullable|datetime',
      'siap_rilis' => 'nullable',
      'acak_soal' => 'nullable',
    ]);
    if(isset($data['siap_rilis'])) $data['siap_rilis'] = true;
    else $data['siap_rilis'] = false;
    if(isset($data['acak_soal'])) $data['acak_soal'] = true;
    else $data['acak_soal'] = false;
    $soals = $data['soal'];
    unset($data['soal']);
    $data['user_id'] = Auth::user()->id;
    $exam = Exam::create($data);
    $exam->questions()->createMany($soals);
    return redirect('/dashboard/manage-exam')->with('success', 'Ujian berhasil dibuat!');
  }
  public function edit_exam(Exam $exam) {
    Gate::authorize('admin');
    return view('users.dashboard.exams.edit', ['exam' => $exam, 'questions' => $exam->questions]);
  }
  public function update_exam(Request $req, Exam $exam) {
    Gate::authorize('admin');
    $data = $req->validate([
      'judul' => ['required', 'string', Rule::unique('exams', 'judul')->ignore($exam->id)],
      'deskripsi' => 'required|string',
      'soal' => 'required|array|list',
      'soal.*.jwbn_yg_benar' => 'required|numeric|max_digits:2',
      'soal.*.soal' => 'required|string',
      'soal.*.jawaban' => 'required|array|list',
      'soal.*.jawaban.*' => 'required|string',
      'deadline' => 'nullable|datetime',
      'siap_rilis' => 'nullable',
      'acak_soal' => 'nullable',
    ]);
    if(isset($data['siap_rilis'])) $data['siap_rilis'] = true;
    else $data['siap_rilis'] = false;
    if(isset($data['acak_soal'])) $data['acak_soal'] = true;
    else $data['acak_soal'] = false;
    $soals = $data['soal'];
    unset($data['soal']);
    $exam->update($data);
    foreach($exam->questions as $i => $question) {
      if(!isset($soals[$i])) {
        $question->delete();
        continue;
      }
      $question->soal = $soals[$i]['soal'];
      $question->jawaban = $soals[$i]['jawaban'];
      $question->jwbn_yg_benar = $soals[$i]['jwbn_yg_benar'];
      $question->save();
    }
    if(count($soals) > $exam->questions->count()) {
      for($i = $exam->questions->count(); $i < count($soals); $i++) {
        Question::create([
          'exam_id' => $exam->id,
          'soal' => $soals[$i]['soal'],
          'jawaban' => $soals[$i]['jawaban'],
          'jwbn_yg_benar' => $soals[$i]['jwbn_yg_benar']
        ]);
      }
    }
    return redirect('/dashboard/manage-exam')->with('success', 'Ujian berhasil diupdate!');
  }
  public function delete_exam(Exam $exam) {
    Gate::authorize('admin');
    $exam->delete();
    return redirect('/dashboard/manage-exam')->with('success', 'Ujian telah dihapus.');
  }
}
