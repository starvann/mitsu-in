<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
      if(!old('email') or !old('nama') or !Session::has('password') or !old('kode')) return redirect('/register');
      return view('users.register2');
    }
    public function create_user(Request $req) {
      $data = $req->validate([
        'nama' => 'required|string|min:3',
        'gmb_profil' => 'nullable|file|image|max:2048',
        'email' => 'email|unique:users,email',
        'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
        'password_confirm' => 'required|same:password',
        'kode' => 'required|string',
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
        }
        
        if($req->hasFile('gmb_profil')) $data['gmb_profil'] = $req->file('gmb_profil')->store('assets/profiles');
        else unset($data['gmb_profil']);
        
        User::create($data);
        return redirect('login')->with('success', 'Pendaftaran berhasil!');
      }
      Session::put('password', $req->input('password'));
      unset($data['password_confirm']);
      return redirect('/register2')->withInput();
    }
    public function create_user2(Request $req) {
      $rules = [];
      $is_any_field_filled = !empty($req->input('relasi_di_jepang.nama')) or !empty($req->input('relasi_di_jepang.relasi'))
        or !empty($req->input('relasi_di_jepang.umur') or !empty($req->input('relasi_di_jepang.pekerjaan'))
        or !empty($req->input('relasi_di_jepang.alamat'))
      if($is_any_field_filled) {
        $rules = [
          'relasi_di_jepang' => 'required|array',
          'relasi_di_jepang.nama' => 'required|string|min:3',
          'relasi_di_jepang.relasi' => 'required|string|min:3',
          'relasi_di_jepang.pekerjaan' => 'required|string|min:3',
          'relasi_di_jepang.umur' => 'required|numeric|integer|max_digits:3',
          'relasi_di_jepang.alamat' => 'required|string|min:3',
        ];
      }
      $rules = $rules + [
        'email' => 'required|email|unique:users,email',
        'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
        'kode' => 'required|string',
        'kode_ref' => 'nullable|max:8|exists:users,kode_ref_saya',
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
        'pendidikan.*.jurusan' => 'required|string|min:3',
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
      if(!in_array($data['kode'], ['admn', 'stdn', 'refl'])) return redirect('/register');
      // set role
      $data['role'] = $data['kode'];
      unset($data['kode']);
      // hash password and delete the session
      $data['password'] = Hash::make($data['password']);
      Session::forget('password');
      // upload profile picture if exists
      if($req->hasFile('gmb_profil')) $data['gmb_profil'] = $req->file('gmb_profil')->store('assets/profiles');
      else unset($data['gmb_profil']);
      $data['status_pernikahan'] = str($data['status_pernikahan'])->ucfirst();
      // create and redirect
      User::create($data);
      return redirect('/login')->with('success', "Pendaftaran Berhasil!");
    }
}
