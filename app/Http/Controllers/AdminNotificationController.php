<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminNotificationController extends Controller
{
    public function index() {
        Gate::authorize('admin');
        $admin = User::find(Auth::id());
        return response()->json([
            "notifications" => $admin->notifications()->latest()->take(20)->get(),
            "unreadCount" => $admin->unreadNotifications()->count()
        ]);
    }

    public function mark_as_read(int $id) {
        Gate::authorize('admin');
        $admin = User::find(Auth::id());
        $admin->notifications()->where('id', $id)->first()?->markAsRead();
        return response();
    }
}
