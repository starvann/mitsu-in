<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function home() {
      return view('home');
    }
    public function login() {
      return view('login');
    }
    public function login_post() {
      
    }
    public function register() {
      return view('register');
    }
    public function register_post() {
      
    }
}
