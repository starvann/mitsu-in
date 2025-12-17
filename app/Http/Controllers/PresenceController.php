<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PresenceController extends Controller
{
  public function store_presence(Request $req) {
    // presensi lewat token (qr/link)
    if($req->query('token')) {
      $id = Cache::get($req->query('token'));
      // pengecekan user id
      if(is_null($id)) return abort(404);
      if(Presence::where('user_id', $id)->whereDate('created_at', today())->exists()) return abort(403);
      // catat presensi
      Presence::create([
        'user_id' => $id,
        'status' => 'hadir'
      ]);
      if(Auth::check()) {
        return redirect('/dashboard')->with('success', 'Berhasil presensi');
      }
      return response('Berhasil presensi');
    }
    if(!Auth::check()) return abort(403);
    if(Presence::where('user_id', Auth::user()->id)->whereDate('created_at', today())->exists()) return abort(403);
    // validasi dan catat izin/sakit
    $data = $req->validate([
      'status' => ['required', Rule::in(['sakit', 'izin', 'darurat'])],
      'alasan' => 'required|min:24',
      'doc_xtra' => 'required|file|max:4096|mimetypes:image/png,image/jpeg,image/webp,application/pdf'
    ]);
    $data['user_id'] = Auth::user()->id;
    $data['doc_xtra'] = $req->file('doc_xtra')->store('assets/presence_docs');
    Presence::create($data);
    return redirect('/dashboard')->with('success', 'Berhasil presensi');
  }
  public function generate_presence_qr(Request $req, int $id) {
    $token = $this->get_presence_token(strval($id));
    $qr = QrCode::generate(url('presence/?token='.$token));
    return response($qr)->header('Content-Type', 'image/svg+xml')->header('Cache-Control', 'no-cache, no-store');
  }
  public function get_presence_data(User $user) {
    // dd();
    if($user->stat == 'pending') return abort(403);
    if(in_array(Auth::user()->role, ['stdn', 'refl']) and Auth::id() != $user->id) return abort(403);
    $data = $this->get_presence_percentage($user);
    $percent = $data['percent'];
    $circumference = 2 * M_PI * 45;
    $offset = $circumference - ($percent / 100) * $circumference;
    $color = $percent <= 50 ? '#ff4444' : ($percent <= 75 ? '#ffaa00' : '#44cc44');
    $svg = "<svg xmlns='http://www.w3.org/2000/svg' id='percentageCircle' width='128' height='128' viewBox='0 0 100 100' >
    <circle cx='50' cy='50' r='45' fill='none' stroke='#e0e0e0' stroke-width='10'/>
    <circle id='progressCircle' cx='50' cy='50' r='45' fill='none' stroke='$color' stroke-width='10' transform='rotate(-90 50 50)' stroke-dasharray='$circumference' stroke-dashoffset='$offset'/>
    <text x='50' y='50' text-anchor='middle' dy='0.3em' font-family='Arial, sans-serif' font-size='20' fill='$color'>$percent%</text>
    </svg>";
    return response()->json([
      'hadir' => $data['hadir'],
      'sakit' => $data['sakit'],
      'izin' => $data['izin'],
      'darurat' => $data['darurat'],
      'alpha' => $data['alpha'],
      'svg' => $svg,
      'tanggal' => $data['tanggal']
    ])->header('Cache-Control', 'no-cache, no-store');
  }
  public function get_presence_percentage(User $user, $year = null, $month = null) {
    $month = $month ?? now();
    $year = $year ?? now();
    $work_days = $this->calc_days_work($year, $month);
    $stats = Presence::where('user_id', $user->id)->period($month, $year)
      ->selectRaw("COUNT(CASE WHEN status = 'hadir' THEN 1 END) as hadir_count, COUNT(CASE WHEN status = 'izin' THEN 1 END) as izin_count, COUNT(CASE WHEN status = 'sakit' THEN 1 END) as sakit_count, COUNT(CASE WHEN status = 'darurat' THEN 1 END) as darurat_count, COUNT(CASE WHEN status = 'alpha' THEN 1 END) as alpha_count")
      ->first();
    $hadir = $stats->hadir_count ?? 0;
    $sakit = $stats->sakit_count ?? 0;
    $izin = $stats->izin_count ?? 0;
    $darurat = $stats->darurat_count ?? 0;
    $alpha = $stats->alpha_count ?? 0;
    $percentage = $work_days > 0 ? ($hadir / $work_days) * 100 : 0;
    return [
      'hadir' => $hadir,
      'izin' => $izin,
      'sakit' => $sakit,
      'darurat' => $darurat,
      'alpha' => $alpha,
      'percent' => round($percentage, 2),
      'tanggal' => "$month->monthName $month->year"
    ];
  }
  public static function get_presence_token(string $id) {
    if(Cache::has('pt_'.$id)) return Cache::get('pt_'.$id)['token'];
    $token = Str::random(32);
    Cache::put('pt_'.$id, [
      'token' => $token
    ], now()->addMinutes(15));
    Cache::put($token, intval($id), now()->addMinutes(15));
    return $token;
  }
  public static function calc_days_work($year, $month) {
    $total_days = Carbon::create($year, $month)->daysInMonth;
    $work_days = 0;
    for($day = 1; $day <= $total_days; $day++) {
      $date = Carbon::create($year, $month, $day);
      if(!$date->isWeekend()) $work_days++;
    }
    return $work_days;
  }
}
