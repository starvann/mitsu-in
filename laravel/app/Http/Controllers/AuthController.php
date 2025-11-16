<?php

namespace App\Http\Controllers;

use App\Models\User
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
      return view('users.register2');
    }
    public function create_user(Request $req) {
      $data = $req->validate([
        'email' => 'nullable|email|unique:users,email',
        'password' => 'required|min:8',
        'code' => 'required|max:4',
      ]);
      return redirect('/register2')->withInput();
    }
    public function create_user2() {
      if(!old('email') or !old('password') or !('code')) return redirect('/login');
      $data = $req->validate([
        'email' => 'nullable|email|unique:users,email',
        'password' => 'required|min:8',
        'code' => 'required|max:4',
      ]);
    }
}
