<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Models\User;
use App\Models\UserDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public static function logout(Request $req) {
      Auth::logout();
      $req->session()->invalidate();
      $req->session()->regenerateToken();
      return redirect('/login');
    }
    public function login() {
      if(Auth::viaRemember()) return redirect()->intended('dashboard');
      if(Session::has('password')) Session::forget('password');
      return view('users.login');
    }
    public function try_login(Request $req) {
      $credential = $req->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:8',
      ]);
      if(Auth::attempt($credential, $req->input('remember_me', false))) {
        $req->session()->regenerate();
        return redirect()->intended('dashboard');
      }
      return back()->withErrors(['Login gagal.']);
    }
    public function register() {
      return view('users.register');
    }
    public function register2() {
      if(!old('email') or !old('nama') or !Session::has('password') or !old('kode') or !old('no_hp') or !old('tanggal_lahir')) return redirect('/register');
      return view('users.register2');
    }
    public function create_user(Request $req) {
      $data = $req->validate([
        'nama' => 'required|string|min:3',
        'gambar_profil' => 'nullable|file|image|max:2048',
        'email' => 'email|unique:users,email',
        'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
        'password_confirm' => 'required|same:password',
        'kode' => 'required|string',
        'no_hp' => 'required|string|digits_between:9,16',
        'tanggal_lahir' => ['required', Rule::date()->before(today()->subYears(17))],
      ]);
      if(!in_array($data['kode'], ['admn', 'stdn', 'refl'])) return redirect('/register')->withErrors(['kode' => 'Kode tidak valid.']);
      if($data['kode'] === 'admn' or $data['kode'] === 'refl') {
        $data['role'] = $data['kode'];
        unset($data['kode']);
        unset($data['password_confirm']);
        // if role is referral set unique ref code
        if($data['role'] == 'refl') {
          $data['kode_ref_saya'] = Str::random(8);
          while(User::where('kode_ref_saya', $data['kode_ref_saya'])->exists()) {
            $data['kode_ref_saya'] = Str::random(8);
          }
          $data['status'] = 'accepted';
        }
        if($req->hasFile('gambar_profil')) $data['gambar_profil'] = $req->file('gambar_profil')->store('assets/profiles');
        else unset($data['gambar_profil']);
        
        $user = User::create($data);
        event(new UserRegistered($user));
        return redirect('login')->with('success', 'Pendaftaran berhasil!');
      }
      Session::put('password', $req->input('password'));
      unset($data['password_confirm']);
      return redirect('/register2')->withInput();
    }
    public function create_user2(Request $req) {
      $rules = [];
      $is_any_field_filled = !empty($req->input('relasi_di_jepang.nama')) or !empty($req->input('relasi_di_jepang.hubungan'))
        or !empty($req->input('relasi_di_jepang.usia')) or !empty($req->input('relasi_di_jepang.pekerjaan'))
        or !empty($req->input('relasi_di_jepang.alamat'));
      if($is_any_field_filled) {
        $rules = [
          'relasi_di_jepang' => 'required|array',
          'relasi_di_jepang.nama' => 'required|string|min:3',
          'relasi_di_jepang.hubungan' => 'required|string|min:3',
          'relasi_di_jepang.pekerjaan' => 'required|string|min:3',
          'relasi_di_jepang.usia' => 'required|numeric|integer|max_digits:3',
          'relasi_di_jepang.alamat' => 'required|string|min:3',
        ];
      }
      $baseUserData = $req->validate([
        'nama' => 'required|string|min:3',
        'gambar_profil' => 'nullable|file|image|max:2048',
        'email' => 'required|email|unique:users,email',
        'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
        'kode' => 'required|string',
        'kode_ref' => 'nullable|string|max:8|exists:users,kode_ref_saya',
        'no_hp' => 'required|string|digits_between:9,16',
        'tanggal_lahir' => ['required', Rule::date()->before(today()->subYears(16))],
      ]);
      $rules = $rules + [
        'gender' => ['required', Rule::in(['laki-laki', 'perempuan'])],
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
        'catatan_extra' => 'nullable|max:512',
      ];
      $userDetailData = $req->validate($rules);
      if(!$is_any_field_filled) {
        unset($userDetailData['relasi_di_jepang']);
      }
      if(!in_array($baseUserData['kode'], ['admn', 'stdn', 'refl'])) return redirect('/register');
      // set role
      $baseUserData['role'] = $userDetailData['kode'];
      unset($baseUserData['kode']);
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
      foreach($idxs as $i) unset($userDetailData['pengalaman'][$i]);
      if(count($userDetailData['pengalaman']) === 0) {
        $userDetailData['pengalaman'] = [];
      }
      // hash password and delete the session
      $baseUserData['password'] = Hash::make($baseUserData['password']);
      Session::forget('password');
      // upload profile picture if exists
      if($req->hasFile('gambar_profil')) $baseUserData['gambar_profil'] = $req->file('gambar_profil')->store('assets/profiles');
      else unset($userDetailData['gambar_profil']);
      $userDetailData['status_pernikahan'] = str($userDetailData['status_pernikahan'])->ucfirst();
      // create and redirect
      try {

        DB::beginTransaction();
        $user = User::create($baseUserData);
        $userDetailData["user_id"] = $user->id;
        UserDetail::create($userDetailData);
        DB::commit();
        event(new UserRegistered($user));
        return redirect('/login')->with('success', "Pendaftaran Berhasil!");
      } catch(Exception $e) {
        DB::rollBack();
        Log::error("Create User failed.", $e->getMessage());
        return redirect('/login')->withErrors(["Registrasi gagal"]);
      }
    }
}
