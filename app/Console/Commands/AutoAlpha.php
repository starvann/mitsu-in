<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Presence;
use Carbon\Carbon;

class AutoAlpha extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-alpha';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto alpha bagi student yang belum presensi.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $users = User::select('id')->where('role', 'stdn')->where('stat', 'accepted')->get();
      $prevDays = today()->startOfMonth()->diffInDays(today()) + 1;
      $year = today()->year;
      $month = today()->month;
      foreach($users as $user) {
        // Hanya cek hari kerja bulan ini.
        for($day = 1; $day <= $prevDays; $day++) {
          $date = Carbon::create($year, $month, $day);
          if($date->weekOfDay < 1 or $date->weekOfDay > 5) continue;
          // jika sudah presensi maka skip
          if(Presence::where('user_id', $user->id)->whereDay('created_at', $date)->exists()) continue;
          // alpha jika masih belum presensi
          Presence::create([
            'user_id' => $user->id,
            'status' => 'alpha'
          ]);
        }
      }
    }
}
