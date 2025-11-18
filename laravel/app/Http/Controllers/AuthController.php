<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function home() {
      return view('home');
    }
    public function login() {
      return view('users.login');
    }
    public function try_login() {
      
    }
    public function register() {
      return view('users.register');
    }
    public function register2() {
      if(!old('email') or !Session::has('password') or !old('code')) return redirect('/register');
      return view('users.register2');
    }
    public function create_user(Request $req) {
      $req->validate([
        'email' => 'email|unique:users,email',
        'password' => 'required|min:8',
        'code' => 'required|max:4',
      ]);
      Session::put('password', $req->input('password'));
      return redirect('/register2')->withInput();
    }
    public function create_user2(Request $req) {
      $data = $req->validate([
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'code' => 'required|string|max:4',
        'ref_code' => 'nullable|max:16',
        'name' => 'required|string|min:3',
        'hp_number' => 'required|string|digits_between:9,16',
        'gender' => ['required', Rule::in(['laki-laki', 'perempuan'])],
        'age' => 'required|numeric|integer|min:1',
        'body_h' => 'required|numeric|integer|min:1',
        'body_w' => 'required|numeric|integer|min:1',
        'have_married' => 'required|boolean',
        'blood_type' => 'required|max:2|alpha:ascii|uppercase',
        'religion' => 'required|string|max:32',
        'have_come_to_jp' => 'required|boolean',
        'have_passport' => 'required|boolean',
        'main_hand' => ['required', Rule::in(['kanan', 'kiri'])],
        'address' => 'required|string|min:9|max:512',
        'education' => 'required|array',
        'education.*.year' => 'required|numeric|integer|digits:4',
        'education.*.school_name' => 'required|string|min:4',
        'education.*.major' => 'required|string|min:3',
        'experience' => 'required|array|list',
        'family_structure' => 'required|array',
        'family_structure.*.relation' => 'required|string|min:3',
        'family_structure.*.name' => 'required|string|min:3',
        'family_structure.*.age' => 'required|numeric|integer|max_digits:3',
        'family_structure.*.job' => 'required|string|min:3',
        'family_structure.*.salary' => 'required|string',
        'purpose_to_jp' => 'required|max:256',
        'purpose_after_comeback' => 'required|max:256',
        'strengths' => 'required|max:256',
        'weaknesses' => 'required|max:256',
        'hobies' => 'required|max:256',
        'has_jlpt_cert' => 'required|boolean',
        'has_sim_a' => 'required|boolean',
        'other_cert' => 'nullable|max:256',
        'jp_relations' => 'required|array',
        'jp_relations.name' => 'nullable|string|min:3',
        'jp_relations.relation' => 'nullable|string|min:3',
        'jp_relations.job' => 'nullable|string|min:3',
        'jp_relations.age' => 'nullable|numeric|integer|max_digits:3',
        'jp_relations.address' => 'nullable|string|min:3',
        'extra_notes' => 'nullable|max:512',
      ]);
      if(!in_array($data['code'], ['admn', 'stdn', 'refl'])) return redirect('/register');
      // set role
      $data['role'] = $data['code'];
      unset($data['code']);
      // hash password and delete the session
      $data['password'] = Hash::make($data['password']);
      Session::forget('password');
      User::create($data);
      return redirect('/login')->with('success', "Pendaftaran Berhasil!");
    }
}
