<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
      if(!old('email') or !old('password') or !('code')) return redirect('/register');
      return view('users.register2');
    }
    public function create_user(Request $req) {
      $req->validate([
        'email' => 'email|unique:users,email',
        'password' => 'required|min:8',
        'code' => 'required|max:4',
      ]);
      return redirect('/register2')->withInput();
    }
    public function create_user2(Request $req) {
      $data = $req->validate([
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'code' => 'required|max:4',
        'ref_code' => 'nullable|max:4',
        'name' => 'required|max:4',
        'hp_number' => 'required|digits_between:9,16',
        'gender' => ['required', Rule::in(['laki-laki', 'perempuan'])],
        'age' => 'required|numeric|integer|min:1',
        'body_h' => 'required|numeric|integer|min:1',
        'body_w' => 'required|numeric|integer|min:1',
        'have_married' => 'required|boolean',
        'blood_type' => 'required|max:2|alpha:ascii',
        'religion' => 'required|max:32',
        'have_come_to_jp' => 'required|boolean',
        'have_passport' => 'required|boolean',
        'main_hand' => ['required', Rule::in(['kanan', 'kiri'])],
        'address' => 'required|max:512',
        'education' => 'required|array|list|in_array_keys:year,school_name,major',
        'experience' => 'required|array|list',
        'family_structure' => 'required|array|list|in_array_keys:relation,name,age,job,salary',
        'purpose_to_jp' => 'required|max:256',
        'purpose_after_comeback' => 'required|max:256',
        'strengths' => 'required|max:256',
        'weaknesses' => 'required|max:256',
        'hobies' => 'required|max:256',
        'has_jlpt' => 'required|boolean',
        'has_sim_a' => 'required|boolean',
        'other_cert' => 'nullable|max:256',
        'jp_relations' => 'required|array|list|in_array_keys:name,relation,job,age,address',
        'extra_notes' => 'nullable|max:512',
      ]);
      dd($data);
    }
}
