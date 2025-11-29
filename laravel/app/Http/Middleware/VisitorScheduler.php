<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Presence;
use App\Http\Controllers\PresenceController;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class VisitorScheduler
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if(!Cache::has('scheduler_last_run')) {
      Cache::put('scheduler_last_run', true, now()->addMinute());
      $this->auto_alpha();
    }
    return $next($request);
  }
  public function auto_alpha() {
    if(now()->hour >= 9 and !Cache::has('auto_alpha')) {
      Cache::put('auto_alpha', true, now()->addHour(12));
      $users = User::select('id')->where('role', 'stdn')->where('stat', 'accepted')->get();
      $prevDays = today()->startOfMonth()->diffInDays(today()) + 1;
      $year = today()->year;
      $month = today()->month;
      foreach($users as $user) {
        for($day = 1; $day <= $prevDays; $day++) {
          $date = Carbon::create($year, $month, $day);
          if($date->weekOfDay < 1 or $date->weekOfDay > 5) continue;
          if(Presence::where('user_id', $user->id)->whereDay('created_at', $date)->exists()) continue;
          Presence::create([
            'user_id' => $user->id,
            'status' => 'alpha'
          ]);
        }
      }
    }
  }
}
