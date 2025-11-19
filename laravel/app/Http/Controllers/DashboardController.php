<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        if(Auth::user()->role == 'admn') return view('users.dashboard.admin');
        else return view('users.dashboard.user');
    }
}
