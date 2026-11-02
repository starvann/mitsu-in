<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exam;
use App\Models\Presence;
use App\Models\Question;
use App\Http\Controllers\PresenceController;
use App\Listeners\NotifyAdminOfNewPaymentProof;
use App\Models\ExamResult;
use App\Models\PaymentProof;
use App\Models\UserDetail;
use App\Notifications\PaymentProofUpdated;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
  public function index(Request $req) {
    $role = Auth::user()->role;
    if(Auth::user()->status == 'pending') {
      return redirect('pending');
    }
    if($role == 'admn') {
      if(Auth::user()->status == 'pending') {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect('/login')->withErrors(['Admin tidak terverifikasi!']);
      }
      return view('users.dashboard.admn');
    }
    elseif($role == 'refl') {
      $refUsers = User::select(['nama', 'gambar_profil', 'status'])->where('kode_ref', Auth::user()->kode_ref_saya)->get();
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
    if($role == 'no-admn') $users = User::select('id', 'nama', 'email', 'status', 'gambar_profil')->where('role', '!=', 'admn');
    else $users = User::select('id', 'nama', 'email', 'status', 'gambar_profil')->where('role', $role);
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
    $users = $users->get();
    return response()->json($users);
  }
  public function edit_user(User $user) {
    if(Gate::none(['same', 'admin'], $user)) return abort(403);
    $data = [
      'user' => $user,
      'isAdmin' => Gate::allows('admin'),
      'back_url' => $user->role == 'stdn' ? url('dashboard/students') : ($user->role == 'refl' ? url('dashboard/referrals') : url('dashboard/admins'))
    ];
    return view('users.dashboard.users.edit', $data);
  }
  public function view_user(User $user) {
    Gate::authorize('admin');
    $data = [
      'user' => $user,
      'back_url' => $user->role == 'stdn' ? url('dashboard/students') : ($user->role == 'refl' ? url('dashboard/referrals') : url('dashboard/admins'))
    ];
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
    if($user->paymentProof) Storage::delete($user->paymentProof->file);
    $user->delete();
    if($user->gambar_profil != 'assets/profiles/default.webp') Storage::delete($user->gambar_profil);
    return redirect('/dashboard/students')->with('success', 'User telah dihapus.');
  }
  public function update_user(Request $req, User $user) {
    if(Gate::none(['same', 'admin'], $user)) return abort(403);
    if($req->has('stat_only') and Gate::allows('admin')) {
      $data = $req->validate([
        'status' => ['required', Rule::in(['pending', 'accepted'])]
      ]);
      $user->update($data);
      return redirect("dashboard/view-user/$user->id")->with('success', 'Berhasil update!');
    }
    $rules = [];
    if(Gate::allows('admin')) {
      $rules += [
        'status' => ['required', Rule::in(['pending', 'accepted'])],
        'role' => ['required', Rule::in(['stdn', 'refl', 'admn'])]
      ];
    }
    $is_any_field_filled = !empty($req->input('relasi_di_jepang.nama')) or !empty($req->input('relasi_di_jepang.hubungan'))
    or !empty($req->input('relasi_di_jepang.usia')) or !empty($req->input('relasi_di_jepang.pekerjaan'))
    or !empty($req->input('relasi_di_jepang.alamat'));
    if($is_any_field_filled) {
      $rules += [
        'relasi_di_jepang' => 'required|array',
        'relasi_di_jepang.nama' => 'required|string|min:3',
        'relasi_di_jepang.hubungan' => 'required|string|min:3',
        'relasi_di_jepang.pekerjaan' => 'required|string|min:3',
        'relasi_di_jepang.usia' => 'required|numeric|integer|max_digits:3',
        'relasi_di_jepang.alamat' => 'required|string|min:3',
      ];
    }
    if($req->has('gambar_profil')) {
      $rules += ['gambar_profil' => 'file|image|max:2048'];
    }
    $baseUserData = $req->validate([
        'nama' => 'required|string|min:3',
        'gambar_profil' => 'nullable|file|image|max:2048',
        'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        'no_hp' => 'required|string|digits_between:9,16',
        'tanggal_lahir' => ['required', Rule::date()->before(today()->subYears(16))],
        'gender' => ['required', Rule::in(['laki-laki', 'perempuan'])],
      ]);
    $rules = $rules + [
      'tinggi_badan' => 'required|numeric|integer|min:1',
      'berat_badan' => 'required|numeric|integer|min:1',
      'status_pernikahan' => 'required|string',
      'golongan_darah' => 'required|max:2|alpha:ascii|uppercase',
      'agama' => 'required|string|max:32',
      'pernah_ke_jepang' => 'required|boolean',
      'punya_paspor' => 'required|boolean',
      'tangan_utama' => ['required', Rule::in(['kanan', 'kiri', 'keduanya'])],
      'alamat' => 'required|string|min:9|max:512',
      'pendidikan' => 'required|array',
      'pendidikan.*.tahun' => 'required|numeric|integer|digits:4',
      'pendidikan.*.nama_sekolah' => 'required|string|min:4',
      'pendidikan.*.jurusan' => 'required|string',
      'pengalaman' => 'required|array|list',
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
      'catatan_extra' => 'nullable|max:512',
    ];
    $userDetailData = $req->validate($rules);
    if(!$is_any_field_filled) {
      $userDetailData['relasi_di_jepang'] = [];
    }
    $idxs = [];
    foreach($userDetailData['pengalaman'] as $i => $exp) {
      if(!is_string($exp)) {
        $idxs[] = $i;
        continue;
      }
      if(strlen($exp) === 0) {
        $idxs[] = $i;
        continue;
      }
    }
    foreach($idxs as $i) {
      unset($userDetailData['pengalaman'][$i]);
    }
    if(count($userDetailData['pengalaman']) === 0) {
      $userDetailData['pengalaman'] = [];
    }
    // upload profile picture if exists
    if($req->hasFile('gambar_profil')) {
      $baseUserData['gambar_profil'] = $req->file('gambar_profil')->store('assets/profiles');
      if($user->gambar_profil != 'assets/profiles/default.webp') Storage::delete($user->gambar_profil);
    }
    else unset($baseUserData['gambar_profil']);
    $userDetailData['status_pernikahan'] = str($userDetailData['status_pernikahan'])->ucfirst();
    try {
      DB::beginTransaction();
      $user->update($baseUserData);
      $user->userDetail()->update($userDetailData);
      DB::commit();
      if(Gate::allows('admin')) {
        return redirect('/dashboard/view-user/'.$user->id)->with('success', 'Berhasil update!');
      }
      return redirect('dashboard');
    } catch(Exception $e) {
      DB::rollBack();
      Log::error("Failed to update user.", $e->getMessage() . "|Trace: " . $e->getTraceAsString());
      return redirect()->back()->withErrors(["Gagal mengubah data pengguna"]);
    }
  }

  public function edit_password(User $user) {
    Gate::authorize('same', $user);
    return view('users.dashboard.change-pass', ['id' => $user->id]);
  }

  public function update_password(Request $req, User $user) {
    Gate::authorize('same', $user);
    $data = $req->validate([
      'password_lama' => 'required|string',
      'password_baru' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
      'ulangi_password_baru' => 'required|same:password_baru'
    ]);
    if(!Hash::check($data['password_lama'], $user->password)) {
      return back()->withErrors(['password_lama' => 'Password lama tidak sama!']);
    }
    $user->update([
      'password' => Hash::make($data['password_baru'])
    ]);
    return redirect('dashboard')->with('success', 'Password telah diupdate!');
  }

  // payments
  public function pending(Request $req) {
    $user = Auth::user();
    if($user->status == 'accepted') {
      return redirect()->intended('dashboard');
    }
    if($user->role == 'admn') {
      Auth::logout();
      $req->session()->invalidate();
      $req->session()->regenerateToken();
      return redirect('/login')->withErrors(['Admin tidak terverifikasi!']);
    }
    return view('users.payment-proof.pending', ['user' => $user]);
  }

  public function view_payment(User $user) {
    return view('users.payment-proof.view', ['user' => $user->load('paymentProof')]);
  }

  public function pending_confirm(Request $req) {
    $data = $req->validate([
      'file' => 'required|file|image|max:2048'
    ]);
    $data['file'] = $req->file('file')->store('assets/payment_proofs');
    $user = User::find(Auth::id());
    $proof = PaymentProof::where('user_id', $user->id)->first();
    if($proof) {
      Storage::delete($proof->file);
      $proof->update($data);
    } else {
      $proof = $user->paymentProof()->create($data);
    }
    event(new PaymentProofUpdated($user));
    return redirect('pending')->with('info', 'Mohon bersabar sampai admin memverifikasi akun anda.');
  }

}
